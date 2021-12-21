'use strict';

let category = null;

function switchCategory(category) {
    if (category === 'drinks') {
        document.getElementById('drinksButton').style.fontWeight = 'bold';
        document.getElementById('foodButton').style.fontWeight = 'normal';
        document.getElementById('welcomeMessage').style.display = 'none';
        document.getElementById('drinks').style.display = 'inline';
        document.getElementById('food').style.display = 'none';
    } else if (category === 'food') {
        document.getElementById('drinksButton').style.fontWeight = 'normal';
        document.getElementById('foodButton').style.fontWeight = 'bold';
        document.getElementById('welcomeMessage').style.display = 'none';
        document.getElementById('drinks').style.display = 'none';
        document.getElementById('food').style.display = 'inline';
    }
}