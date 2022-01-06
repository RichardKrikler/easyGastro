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

function modifyFoodModal(food) {
    document.getElementById('foodTitle').textContent = food;
    for (const foodKey in foodList) {
        if (food.toString() === foodList[foodKey]['bezeichnung'].toString()) {
            document.getElementById('foodPrice').textContent =
                (parseFloat(foodList[foodKey]['preis']) * parseFloat(document.getElementById('foodCount').value)).toFixed(2).toString()
                + '€';
            break;
        }
    }
}

function modifyDrinkModal(drink) {
    document.getElementById('drinkTitle').textContent = drink;
    let drinkAmountElements = document.getElementsByClassName('drinkAmountElement');
    while (drinkAmountElements[0]) {
        drinkAmountElements[0].parentNode.removeChild(drinkAmountElements[0]);
    }
    for (const drinkKey in drinkList) {
        if (drink.toString() === drinkList[drinkKey]['bezeichnung'].toString()) {
            for (const drinkAmountKey in drinkAmountList) {
                if (drinkList[drinkKey]['pk_getraenk_id'].toString() === drinkAmountList[drinkAmountKey]['fk_pk_getraenk_id'].toString()) {
                    for (const amountKey in amountList) {
                        let firstElement = true;
                        if (amountList[amountKey]['pk_menge_id'].toString() === drinkAmountList[drinkAmountKey]['fk_pk_menge_id'].toString()) {
                            let drinkAmountElement = document.createElement('option');
                            drinkAmountElement.appendChild(document.createTextNode(amountList[amountKey]['wert'].toString() + 'l'));
                            drinkAmountElement.setAttribute('value', amountList[amountKey]['wert'].toString());
                            if (firstElement) {
                                drinkAmountElement.setAttribute('selected', '');
                                firstElement = false;
                            }
                            drinkAmountElement.setAttribute('class', 'text-center drinkAmountElement');
                            document.getElementById('drinkAmount').appendChild(drinkAmountElement);
                            break;
                        }
                    }
                }
            }
            break;
        }
    }
    refreshDrinkModalPrice();
}

function refreshDrinkModalPrice() {
    let drinkTitle = document.getElementById('drinkTitle').textContent.toString();
    let selectedDrinkAmount = document.getElementById('drinkAmount').value.toString();
    let drinkCount = document.getElementById('drinkCount').value.toString();
    for (const drinkKey in drinkList) {
        if (drinkTitle === drinkList[drinkKey]['bezeichnung'].toString()) {
            for (const drinkAmountKey in drinkAmountList) {
                if (drinkList[drinkKey]['pk_getraenk_id'].toString() === drinkAmountList[drinkAmountKey]['fk_pk_getraenk_id'].toString()) {
                    for (const amountKey in amountList) {
                        if (amountList[amountKey]['pk_menge_id'].toString() === drinkAmountList[drinkAmountKey]['fk_pk_menge_id'].toString()
                            && amountList[amountKey]['wert'].toString() === selectedDrinkAmount) {
                            document.getElementById('drinkPrice').textContent =
                                (parseFloat(drinkAmountList[drinkAmountKey]['preis']) * parseFloat(drinkCount)).toFixed(2).toString()
                                + '€';
                            break;
                        }
                    }
                }
            }
            break;
        }
    }
}