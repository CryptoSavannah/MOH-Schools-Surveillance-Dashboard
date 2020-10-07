(function getSchools() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            document.querySelector('#total_no_cases').innerHTML = resdata.length;
            document.querySelector('#totaL').innerHTML = resdata.length;
            let casedata = [];
            resdata.cases.forEach(a_case => {
                if (!casedata.includes(a_case.school)) {
                    casedata.push(a_case.school);
                }
            });
            document.querySelector('#total').innerHTML = casedata.length;

        })
})();

(function getMostPrevalent() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            let casedata = [];
            resdata.cases.forEach(a_case => {
                casedata.push(a_case.disease);
                let counts = casedata.reduce((a, b) => {
                    a[b] = (a[b] || 0) + 1;
                    return a;
                }, {});
                let sorted = Object.keys(counts).sort((a, b) => counts[b] - counts[a]);
                let sortedNo = Object.values(counts).sort((a, b) => b - a);
                let top5 = sorted.slice(0, 3);
                let top5No = sortedNo.slice(0, 3);
                google.charts.load('current', { packages: ['corechart'] });
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    // Define the chart to be drawn.
                    var data = new google.visualization.DataTable();
                    data.addColumn('string', 'Element');
                    data.addColumn('number', 'Percentage');
                    data.addRows([
                        [top5[0], Number((top5No[0] / (resdata.cases.length) * 100).toFixed(2))],
                        [top5[1], Number((top5No[1] / (resdata.cases.length) * 100).toFixed(2))],
                        [top5[2], Number((top5No[2] / (resdata.cases.length) * 100).toFixed(2))]
                    ]);

                    // Instantiate and draw the chart.
                    var chart = new google.visualization.PieChart(document.getElementById('myPieChart'));
                    chart.draw(data, null);
                }
                let percentage = (top5No[0] / (resdata.cases.length) * 100).toFixed(2) + '%';
                let percentage2 = (top5No[1] / (resdata.cases.length) * 100).toFixed(2) + '%';
                let percentage3 = (top5No[2] / (resdata.cases.length) * 100).toFixed(2) + '%';
                document.querySelector('#most_prevalent').innerHTML = top5[0];
                document.getElementById('disease1').innerHTML = top5[0];
                document.getElementById('disease2').innerHTML = top5[1];
                document.getElementById('disease3').innerHTML = top5[2];
                document.getElementById('numdisease1').innerHTML = top5No[0];
                document.getElementById('perdisease1').innerHTML = percentage;
                document.getElementById('perdisease2').innerHTML = percentage2;
                document.getElementById('perdisease3').innerHTML = percentage3;
            });
        })
})();


function initMap() {

    let everlight = { lat: 0.278113, lng: 32.620594 };
    let vine = { lat: 0.387337, lng: 32.544328 };
    let map = new google.maps.Map(
        document.getElementById('map'), { zoom: 6, center: everlight });

    const contentString =
        '<div id="content">' +
        '<div id="siteNotice">' +
        "</div>" +
        '<h1 id="firstHeading" class="firstHeading">Everlight College</h1>' +
        '<div id="bodyContent">' +
        "<p><b>District:</b> Kampala</p>" +
        "<p><b>Sub-County:</b> Makindye</p>" +
        "<p><b>Parish:</b> Ggaba</p>" +
        "<p><b>Village:</b> Kalungu A</p>" +
        "<h4>18 Cases</h4>"
    "</div>" +
        "</div>";
    let infowindow = new google.maps.InfoWindow({
        content: contentString,
    });
    let marker = new google.maps.Marker({
        position: everlight,
        map,
    });
    marker.addListener("click", () => {
        infowindow.open(map, marker);
    });

    const contentString2 =
        '<div id="content">' +
        '<div id="siteNotice">' +
        "</div>" +
        '<h3 id="firstHeading" class="firstHeading">Vine international christian academy</h3>' +
        '<div id="bodyContent">' +
        "<p><b>District:</b> Wakiso</p>" +
        "<p><b>Sub-County:</b> Kira</p>" +
        "<p><b>Parish:</b> Kira</p>" +
        "<p><b>Village:</b> Kungu A</p>" +
        "<h4>1342 Cases</h4>"
    "</div>" +
        "</div>";
    let infowindow2 = new google.maps.InfoWindow({
        content: contentString2,
    });
    let marker2 = new google.maps.Marker({
        position: vine,
        map,
    });
    marker2.addListener("click", () => {
        infowindow2.open(map, marker2);
    });
}