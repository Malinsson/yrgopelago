const api_key = window.apiKey;

const Url = 'https://www.yrgopelag.se/centralbank';

const data = {
    "user": "Malin",
    "transferCode": `${api_key}`,
}

const formData = new URLSearchParams();
formData.append('user', 'Malin');
formData.append('transferCode', api_key);

fetch(Url+'/islandFeatures', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: formData.toString()
})
    .then(response => response.json())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error fetching data:', error);
    });