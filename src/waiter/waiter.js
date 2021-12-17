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

let clickedTable = 0;
let priceOfTable = 0;

function reply_click(clicked_id) {
    document.getElementById('givenMoney').textContent = '';
    clickedTable = parseFloat(clicked_id.substring(clicked_id.indexOf('_') + 1));
    priceOfTable = parseFloat(document.getElementById('price' + clickedTable).textContent);
}

$(document).ready(function () {
    $(document).on('keyup', 'input[name="givenMoney"]', function () {
        let contents = $(this).val();
        $('input[name="backMoney"]').val((contents - priceOfTable).toFixed(2));
    });
});

//Anzeige des Inhalts
const channel = new BroadcastChannel('sw-messages');
channel.addEventListener('message', event => {
    if(event.data.msg.substring(0,5) === 'Tisch') {
        document.getElementById('table_' + event.data.data).classList.add('active');
    }
})
