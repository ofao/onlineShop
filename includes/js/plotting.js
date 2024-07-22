var years = [1700, 1750, 1800, 1850, 1900, 1950, 2000, 2050];
 
var population = {
    labels: years,
    datasets: [
        {
            label: 'Товар1',
            borderColor: "rgba(220,180,0,1)",
            pointColor: "rgba(220,180,0,1)",
            lineTension: 0,
            fill: false,
            data: [106, 106, 107, 111, 133, 221, 783, 2478]
        }, {
            label: 'Товар2',
            borderColor: "rgba(94,5,115,1)",
            pointColor: "rgba(151,187,205,1)",
            lineTension: 0,
            fill: false,
            data: [12, 18, 31, 64, 156, 339, 820, 1217]
        }, {
            label: 'Товар3',
            borderColor: "rgba(151,187,205,1)",
            pointColor: "rgba(151,187,205,1)",
            lineTension: 0,
            fill: false,
            data: [411, 502, 635, 809, 947, 1402, 3700, 5267]
        }, {
            label: 'Товар4',
            borderColor: "rgba(46,103,219,1)",
            pointColor: "rgba(151,187,205,1)",
            lineTension: 0,
            fill: false,
            data: [178, 190, 203, 276, 408, 547, 675, 734]
        }, {
            label: 'Товар5',
            borderColor: "rgba(189,50,189 ,1)",
            pointColor: "rgba(151,187,205,1)",
            lineTension: 0,
            fill: false,
            data: [3, 2, 2, 2, 6, 13, 30, 57]
        }, {
            label: 'World',
            borderColor: "rgb(3,202,103)",
            pointColor: "rgba(3,202,103,1)",
            lineTension: 0,
            fill: false,
            data: [710, 791, 978, 1262, 1650, 2521, 6008, 9725]
        },
    ]
};

var continentsArea = {
    labels: ['Товар1', 'Товар2', 'Товар3', 'Товар4', 'Товар4', 'Товар5'],
    datasets: [
    {
        backgroundColor: [
           'rgba(234,223,74, 0.5)',
           'rgba(252,21,56 , 0.2)',
           'rgba(211,213,232 , 0.2)',
           'rgba(75, 192, 192, 0.2)',
           'rgba(153, 102, 255, 0.2)',
           'rgba(255, 159, 64, 0.2)'
       ],
       borderColor: [
           'rgba(234,223,74, 0.8)',
           'rgba(252,21,56 , 0.8)',
           'rgba(211,213,232, 0.8)',
           'rgba(83,210,189, 0.8)',
           'rgba(153, 102, 255, 0.8)',
           'rgba(255, 159, 64, 0.8)'
       ],
       data: [30, 28, 20, 7, 9, 6]
     },
   ]
};

plot();
plotPie();
function plot() {
    var context = document.getElementById('canvas').getContext('2d');
    var populationChart = new Chart(
        context,
        {
            type: 'line',
            data: population,
        }
    ); 
}

function plotPie() {
    var context = document.getElementById('canvasPie').getContext('2d');
    var continentsChart = new Chart(
       context,
       {
           type: 'pie',
           data: continentsArea
       }
    ); 
}
