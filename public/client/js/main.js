(function() {
    let init = function() {
        const request = {
            'method': 'GET',
            'url': '/api/v1/ranges/',
            'action': function () {
                console.log(this.response);
                let ranges = [];
                this.response.forEach(function (item, index) {
                    ranges.push(rangeRow(item));
                });
                ranges.push(createRow());
                document.getElementById('ranges').innerHTML = ranges.join("\n");
                document.getElementById('create').onclick = function (e) {
                    add(e.target);
                }
            },
            'error': function () {
                console.error('Ranges retrieving fail');
            }
        };
        apiCall(request);
    };

    let rangeRow = function(row) {
        return [
            '<div>',
            `<input type="date" name="start" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="${row.start}">`,
            `<input type="date" name="end" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" value="${row.end}">`,
            `<input type="number" name="price" step="0.01" min="0" value="${row.price}">`,
            '<input type="button" value="Update" id="update">',
            '<input type="button" value="Delete" id="delete">',
            '</div>'
        ].join("\n");
    };

    let createRow = function() {
        return [
            '<div>',
            '<input type="date" name="start" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" placeholder="start date (2019-07-10)">',
            '<input type="date" name="end" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" placeholder="end date (2019-07-21)">',
            '<input type="number" name="price" step="0.01" min="0" placeholder="range price (1.00)">',
            '<input type="button" value="Add" id="create">',
            '</div>'
        ].join("\n");
    };

    let add = function(target) {
        const body = {
            'start': target.parentNode.querySelector("input[name=start]").value,
            'end': target.parentNode.querySelector("input[name=end]").value,
            'price': target.parentNode.querySelector("input[name=price]").value
        };
        const request = {
            'method': 'PUT',
            'url': '/api/v1/ranges/',
            'body': body,
            'action': function () {
                init();
            },
            'error': function () {
                console.error('Ranges retrieving fail');
            }
        };
        apiCall(request);
    };

    let apiCall = function(request) {
        const Http = new XMLHttpRequest();
        Http.responseType = 'json';
        Http.open(request.method, request.url);
        Http.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        Http.onload = request.action;
        Http.onerror = request.error;
        Http.send(JSON.stringify(request.body));
    };

    init();
})();

