'use strict';

let orderCounter = 0;

function switchCategory(category) {
    document.getElementById('startHeader').style.display = 'none';
    document.getElementById('welcomeMessage').style.display = 'none';
    document.getElementById('payIcon').textContent = 'payments';
    document.getElementById('orderIcon').textContent = 'assignment';
    let orderCounterElement = document.createElement('div');
    orderCounterElement.appendChild(document.createTextNode(orderCounter.toString()))
    orderCounterElement.setAttribute('id', 'orderCounter');
    document.getElementById('orderIcon').appendChild(orderCounterElement);
    if (category === 'drinks') {
        document.getElementById('drinksButton').style.fontWeight = 'bold';
        document.getElementById('foodButton').style.fontWeight = 'normal';
        document.getElementById('drinks').style.display = 'inline';
        document.getElementById('food').style.display = 'none';
        document.getElementById('drinkHeader').style.display = 'inline';
        document.getElementById('foodHeader').style.display = 'none';
    } else if (category === 'food') {
        document.getElementById('drinksButton').style.fontWeight = 'normal';
        document.getElementById('foodButton').style.fontWeight = 'bold';
        document.getElementById('drinks').style.display = 'none';
        document.getElementById('food').style.display = 'inline';
        document.getElementById('drinkHeader').style.display = 'none';
        document.getElementById('foodHeader').style.display = 'inline';
    }
}