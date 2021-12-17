let counter = 0;
let lastData;

const getData = () => {
    fetch('/waiter/getTableInformation.php')
        .then(response => response.json())
        .then(data => {
            if (JSON.stringify(data) !== JSON.stringify(lastData)) {
                lastData = data;
                if (counter > 0) {
                    location.reload();
                }
                counter++;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        })
};

const interval = setInterval(() => {
    getData();
}, 10000);

//Input von Rückgeld in Retourgeld anzeigen
[...document.getElementsByClassName('given-money-field')].forEach(gmf => gmf.addEventListener('keyup', () => {
    const backMoneyField = [...document.getElementsByClassName('back-money-field')].find(bmf => bmf.getAttribute('table') === gmf.getAttribute('table'))
    const fullPrice = [...document.getElementsByClassName('full-price-text')].find(fpt => fpt.getAttribute('table') === gmf.getAttribute('table'))
    backMoneyField.value = (Number(gmf.value - fullPrice.innerHTML)).toFixed(2);
}))


//Anzeige des Inhalts
const channel = new BroadcastChannel('sw-messages');
channel.addEventListener('message', event => {
    document.getElementById('table_' + event.data.data).classList.add('active');
})
