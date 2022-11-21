const limitedSpecial = document.querySelectorAll("[limited-special]"); //selects all elements with attribute limited-special
const lettersOnly = document.querySelectorAll("[letters-only]");
const noNumber = document.querySelectorAll("[no-number]");

var specialCharacters = /[`~!@#$%^&*_\[\]\{\};"?|\\<>]/gm;
var allSpecialCharacters = /[~!@#$%^&*()+_\{\}\[\]|\\:;"'<>,\/.]/gm;
var numbers = /[0-9]/gm;

// For every input element in limitedSpecial array,
// add a keydown listener
limitedSpecial.forEach(input => {
    input.addEventListener("keydown", e => {
        // If the key pressed is in specialCharacters RegExp, ignore
        if (e.key.match(specialCharacters)) {
            e.preventDefault();
        }
    });
});

lettersOnly.forEach(input => {
    input.addEventListener("keydown", e => {
        if(e.key.match(allSpecialCharacters) || e.key.match(numbers)) {
            e.preventDefault();
        }
    });
});

noNumber.forEach(input => {
    input.addEventListener("keydown", e => {
        if(e.key.match(e.key.match(numbers))) {
            e.preventDefault();
        }
    });
});