(function getTotalNoCases() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            console.log(resdata);
            document.querySelector('#total_no_cases').innerHTML = resdata.length;
        })
})();

(function getSchools() {
    fetch('http://localhost:5000/api/v1/cases', {
        headers: {
            "Content-Type": "application/json",
            "Access-Control-Allow-Origin": "*"
        }
    }).then(response => response.json())
        .then(resdata => {
            console.log(resdata);
            let casedata = [];
            resdata.cases.forEach(a_case => {
                if (!casedata.includes(a_case.school)) {
                    casedata.push(a_case.school);
                }
            });
            console.log(casedata);
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
                let counts = casedata.reduce((a, c) => {
                    a[c] = (a[c] || 0) + 1;
                    return a;
                }, {});
                let maxCount = Math.max(...Object.values(counts));
                let mostFrequent = Object.keys(counts).filter(k => counts[k] === maxCount);
                console.log(mostFrequent[0]);
                document.querySelector('#most_prevalent').innerHTML = mostFrequent[0];
            });
        })
})();