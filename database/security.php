<?php

class AuthDB
 {
    private PDOStatement $statementReadSession;
    private PDOStatement $statementReadUser;
    private PDOStatement $statementReadUserFromEmail;
    private PDOStatement $statementCreateSession;
    private PDOStatement $statementSetLastLogin;
    private PDOStatement $statementDeleteSession;
    private PDOStatement $statementReadUserGroup;
    private PDOStatement $statementRegister;
    private PDOStatement $statementUpdateLoginStatus;
    private PDOStatement $statementCheckLoginStatus;
    private PDOStatement $statementUpdateUser;

    function __construct(private PDO $pdo)
    {
        
        $this->statementReadSession = $pdo->prepare('SELECT * from session WHERE id=:id');
        $this->statementReadUser =  $pdo->prepare('SELECT * FROM user WHERE id_user=:id_user');
        $this->statementReadUserFromEmail = $pdo->prepare('SELECT * FROM user WHERE email=:email');
        $this->statementCreateSession = $pdo->prepare('INSERT INTO session VALUES 
        (
            :sessionid,
            :userid
        )');
        $this->statementReadUserGroup = $pdo->prepare("SELECT COUNT(*) FROM user_group WHERE id_user = :id_user AND id_group = :id_group");
        $this->statementSetLastLogin = $pdo->prepare('UPDATE user SET last_login=NOW() WHERE id_user=:id_user');
        $this->statementDeleteSession = $pdo->prepare('DELETE FROM session WHERE id=:id');
        $this->statementRegister = $pdo->prepare('INSERT INTO user VALUES(DEFAULT,:username, :email,:password, NULL,1)');
        $this->statementUpdateLoginStatus = $pdo->prepare('UPDATE user SET is_loggedin = 1 WHERE id_user = :user_id');
        $this->statementCheckLoginStatus = $this->pdo->prepare('SELECT is_loggedin FROM user WHERE id_user = :user_id');
        $this->statementUpdateUser = $this->pdo->prepare("UPDATE user SET is_loggedin = :is_loggedin WHERE id_user = :id_user ");
    }

    function login(int $userId)
    {
        $sessionId = bin2hex(random_bytes(32));
        $this->statementCreateSession->bindValue(':userid', $userId);
        $this->statementCreateSession->bindValue(':sessionid', $sessionId);
        $this->statementCreateSession->execute();
        $signature = hash_hmac('sha256', $sessionId, '4cd30a3e9bd36ae867730f712e15b4d29d0473916d5d61e8425346f277c63cf9');
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
        setcookie('signature', $signature, time() + 60 * 60 * 24 * 14, "", "", false, true);
        setcookie('session', $sessionId, time() + 60 * 60 * 24 * 14, '', '', false, true);
        $this->statementUpdateLoginStatus->bindValue(':user_id', $userId);
        $this->statementUpdateLoginStatus->execute();
        return;
    }

    function setLastLogin(int $userId)
    {
        $this->statementSetLastLogin->bindParam(':id_user', $userId, PDO::PARAM_INT);
        $this->statementSetLastLogin->execute();
        return;
    }

    function register(array $user):void
    {
   
        $hashedPassword = password_hash($user['password'],PASSWORD_ARGON2ID);
        $this->statementRegister->bindValue(':username',$user['username']);
        $this->statementRegister->bindValue(':email',$user['email']);
        $this->statementRegister->bindValue(':password',$hashedPassword);
        $this->statementRegister->execute();
        $userId = $this->pdo->lastInsertId();
        $this->login($userId);
        
        $this->statementRegister->execute();

        return;
    }

    function userIsInGroup($user_id, $group_id) {
        $this->statementReadUserGroup->bindValue(':id_user', $user_id);
        $this->statementReadUserGroup->bindValue(':id_group', $group_id);
        $this->statementReadUserGroup->execute();
        $count = $this->statementReadUserGroup->fetchColumn();
        return $count > 0;
    }

    function updateLoginStatus($user_id, $is_loggedin) {
        $this->statementUpdateLoginStatus->bindValue(':id_user', $user_id);
        $this->statementUpdateLoginStatus->bindValue(':is_loggedin', $is_loggedin);
        $this->statementUpdateLoginStatus->execute();
    }

    function checkLoginStatus($user_id) {
        $this->statementCheckLoginStatus->bindValue(':user_id', $user_id);
        $this->statementCheckLoginStatus->execute();
        return $this->statementCheckLoginStatus->fetch()['is_loggedin'];
    }

    function isLoggedin(): array | false
    {
        $sessionId = $_COOKIE['session'] ?? '';
        $signature = $_COOKIE['signature'] ?? '';
        if ($sessionId && $signature) {
            $hash = hash_hmac('sha256', $sessionId, '4cd30a3e9bd36ae867730f712e15b4d29d0473916d5d61e8425346f277c63cf9');
                if (hash_equals($hash, $signature)) {
                $this->statementReadSession->bindValue(':id', $sessionId);
                $this->statementReadSession->execute();
                $session =  $this->statementReadSession->fetch();
                if ($session) {
                    $this->statementReadUser->bindParam(':id_user', $session['userid'], PDO::PARAM_INT);                    $this->statementReadUser->execute();
                    $user = $this->statementReadUser->fetch();
                    if($user['is_loggedin'] === 1)
                    return $user;
                }
              }
            }
        return $user ?? false;
  }


    function logout(string $sessionId, $user)
    {
        $currentUser = $this->isLoggedin();
        $this->statementUpdateUser->bindValue(':is_loggedin', 0);
        $this->statementUpdateUser->bindValue(':id_user', $currentUser['id_user']);
        $this->statementUpdateUser->execute();
        
        $this->statementDeleteSession->bindValue(':id', $sessionId);
        $this->statementDeleteSession->execute();
        
        setcookie('session', '', time() - 1);
        return;
    }

    function getUserFromEmail(string $email)
    {
      $this->statementReadUserFromEmail->bindValue(':email', $email);
      $this->statementReadUserFromEmail->execute();
      return $this->statementReadUserFromEmail->fetch();
    }
}

return new AuthDB($pdo);
