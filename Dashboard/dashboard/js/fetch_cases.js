"use strict";

(function getCases() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            console.log(resdata);
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
            console.log(typeof (table));

            console.log(resdata.schools);
            console.log(resdata.cases);
            var x = document.getElementById("schools");
            let school;
            let option;
            for (school = 0; school < resdata.schools.length; school++) {
                option = document.createElement("option");
                option.text = resdata.schools[school];
                option.value = resdata.schools[school];
                console.log(option.text);
                x.add(option);
                document.getElementById("school_name").innerHTML = resdata.schools[0];
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
            console.log(resdata);
            let name = document.getElementById("school_name");
            let total = document.getElementById("sch_total");
            console.log(school);
            if (school === "select") {
                name.innerHTML = '';
                total.innerHTML = 'resdata.length';
            } else {
                let casedata = [];
                resdata.cases_per_sch.forEach(a_case => {
                    casedata.push(a_case.disease);
                    let counts = casedata.reduce((a, c) => {
                        a[c] = (a[c] || 0) + 1;
                        return a;
                    }, {});
                    let maxCount = Math.max(...Object.values(counts));
                    let mostFrequent = Object.keys(counts).filter(k => counts[k] === maxCount);
                    console.log(mostFrequent[0]);
                    document.querySelector('#school_most_prevalent').innerHTML = mostFrequent[0];
                });
            }
            name.innerHTML = school + ', Parish, Sub-County, District';
            total.innerHTML = resdata.length;
        })
}