let functionAlreadyExecuted = false;

function autoCorrect() {
  if (!functionAlreadyExecuted) {
    var myAnchor = document.querySelector("#clickblock a");
    var text = $(myAnchor).html();
    var correctedText = text.replace(
      /fotes|fraz|sete|dan|Cliké|ici|lé/g,
      function (matched) {
        switch (matched) {
          case "fotes":
            return "fautes";
          case "fraz":
            return "phrase";
          case "sete":
            return "cette";
          case "dan":
            return "dans";
          case "Cliké":
            return "Cliquez";
          case "ici":
            return "ici";
          case "lé":
            return "les";
          default:
            return matched;
        }
      }
    );
    $(myAnchor).html(correctedText);
    functionAlreadyExecuted = true;
  }
}
