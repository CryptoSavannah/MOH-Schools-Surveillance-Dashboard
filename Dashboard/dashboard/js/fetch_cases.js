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
        })
})();