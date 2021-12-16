let counter = 0;
let lastData;

const getData = () => {
    fetch('/kitchen/getOrderInformation.php')
        .then(response => response.json())
        .then(data => {
            if(JSON.stringify(data) !== JSON.stringify(lastData)){
                lastData = data;
                if(counter > 0){
                    location.reload();
                }
                counter++;
            }
        })
        .catch((error) => {
            console.error('Error:', error);
        })};

const interval = setInterval(() => {
    getData();
}, 10000);