"use strict";

(function getCases() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            let the_data = resdata.cases;
            let table = $("#the_dataTable").DataTable(
                {
                    data: the_data,
                    columns: [
                        { data: 'case_no' },
                        { data: 'disease' },
                        { data: 'patient_id' },
                        { data: 'school' },
                        { data: 'parish' },
                        { data: 'sub_county' },
                        { data: 'district' }
                    ]
                });
            var x = document.getElementById("schools");
            let school;
            let option;
            for (school = 0; school < resdata.schools.length; school++) {
                option = document.createElement("option");
                option.text = resdata.schools[school];
                option.value = resdata.schools[school];
                x.add(option);
            }

        })
})();

function getCasesPerSch(school) {
    fetch(`http://localhost:5000/api/v1/${school}/cases`, {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            let name = document.getElementById("school_name");
            let total = document.getElementById("sch_total");
            name.innerHTML = school;
            total.innerHTML = resdata.length;
            if (school === "select") {
                document.getElementById("report").style.display = 'none';
            } else {
                document.getElementById("report").style.display = 'block';
                let casedata = [];
                resdata.cases_per_sch.forEach(a_case => {
                    casedata.push(a_case.disease);
                    let counts = casedata.reduce((a, c) => {
                        a[c] = (a[c] || 0) + 1;
                        return a;
                    }, {});
                    let sorted = Object.keys(counts).sort((a, b) => counts[b] - counts[a]);
                    let sortedNo = Object.values(counts).sort((a, b) => b - a);
                    let top5 = sorted.slice(0, 3);
                    let top5No = sortedNo.slice(0, 3);
                    let percentage = (top5No[0] / (casedata.length) * 100).toFixed(2) + '%';
                    let percentage2 = (top5No[1] / (casedata.length) * 100).toFixed(2) + '%';

                    document.querySelector('#school_most_prevalent').innerHTML = top5[0];
                    document.querySelector('#school_most_prevalent_1').innerHTML = top5[0];
                    document.querySelector('#school_most_prevalent_2').innerHTML = top5[1];
                    document.getElementById('number2').innerHTML = percentage2;
                    document.getElementById('percentage').innerHTML = percentage;
                    document.getElementById('danger').style.width = percentage;
                    document.getElementById('indicator').style.width = percentage;
                    document.getElementById('warning').style.width = percentage2;
                });
            }

        })
}