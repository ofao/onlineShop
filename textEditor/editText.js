function makeBold() {
  document.execCommand('bold', false, null);
}

function makeItalic() {
  document.execCommand('italic', false, null);
}

function makeUnderline() {
  document.execCommand('underline', false, null);
}

function makeStrike() {
  document.execCommand('strikethrough', false, null);
}

function top() {
  document.execCommand('superscript', false, null);
}

function bottom() {
  document.execCommand('subscript', false, null);
}

function makeUl() {
  document.execCommand('insertUnorderedList', false, null);
}

function makeOl() {
  document.execCommand('insertOrderedList', false, null);
}

function alignLeft() {
  document.execCommand('justifyLeft', false, null);
}

function alignCenter() {
  document.execCommand('justifyCenter', false, null);
}

function alignRight() {
  document.execCommand('justifyRight', false, null);
}

function alignFull() {
  document.execCommand('justifyFull', false, null);
}

function f6() {
  document.getElementById("textarea1").style.textTransform = "uppercase";
}

function f7() {
  document.getElementById("textarea1").style.textTransform = "lowercase";
}

function f8() {
  document.getElementById("textarea1").style.textTransform = "capitalize";
}

function clearStyle() {
  document.execCommand('removeFormat', false, null);
}

function f10() {
  const content = document.getElementById("textarea1").value;
  var save = document.createElement("a");
  save.setAttribute("href", "data:text/plain;charset=umenttttf-8," + encodeURI(content));
  save.setAttribute("download", content.slice(0, 17) + ".txt");

  document.body.appendChild(save);
  save.click();
  document.body.removeChild(save);
}
