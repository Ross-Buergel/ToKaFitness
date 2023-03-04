function countText() {
  //gets the length of the message
  let text = document.form.text.value;
  document.getElementById('characters').innerText = text.length;
}

function changeColour(php_colour_type) {
  //assigns user input to a variable
  let colourType = String(php_colour_type);

  //assigns different colours based on user input
  if (colourType === "Red-Green") {
    document.documentElement.style.setProperty('--secondary-colour', '#0a1899');
    document.documentElement.style.setProperty('--hover-colour', '#0a1899');
  }
  else if (colourType === "Blue-Yellow") {
    document.documentElement.style.setProperty('--secondary-colour', '#107a17');
    document.documentElement.style.setProperty('--hover-colour', '#0d5e13');
  }
}