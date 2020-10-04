function toFinish(index) {
    // start ajax update
    var httpRequest = new XMLHttpRequest()
    httpRequest.onreadystatechange = function () {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                var result = httpRequest.responseText
                if (result == 'Update failed') {
                    var element = document.querySelector('#dropdownMenuButtonStatus' + index)
                    element.innerHTML = result
                }
            } else {
                alert('Impossible de contacter le serveur')
            }
        }
    }
    // set method & url to call asynchronously
    httpRequest.open('POST', 'index.php?p=anime.finish&id=' + index, true)
    // build form map object to send
    httpRequest.send(null)
}

function setStatusRemove(index, newStatus) {
    if (newStatus == 1) {
        toFinish(index)
    } else {
        // set status de l'anime
        updateFieldWithAjax(index, 'status', newStatus)
    }

    // destroy the line in tab
    document.querySelector('#anime' + index).remove()
}

function setStatus(index, newStatus) {
    if (newStatus == 1) {
        toFinish(index)
    } else {
        // set status de l'anime
        updateFieldWithAjax(index, 'status', newStatus)
    }

    // update label and color after change
    /*console.log(element)
    // get class bg
    var beforeBG
    for(var i = 0; i < element.classList.length; i++) {
        var className = element.classList.item(i)
        if(className.includes('bg-')){
            beforeBG = className
        }
    }

    element.innerHTML = 'a'*/
}
function add(index, field) {
    var element = document.querySelector('#' + field + index)
    var newValue = parseInt(element.innerHTML, 10)
    updateFieldWithAjax(index, field, newValue + 1)
}

function remove(index, field) {
    var element = document.querySelector('#' + field + index)
    var newValue = parseInt(element.innerHTML, 10)
    updateFieldWithAjax(index, field, newValue - 1)
}

function updateFieldWithAjax(index, field, value) {
    // start ajax update
    var httpRequest = new XMLHttpRequest()
    httpRequest.onreadystatechange = function () {
        if (httpRequest.readyState === 4) {
            if (httpRequest.status === 200) {
                var result = httpRequest.responseText
                displayResult(index, field, result)
            } else {
                alert('Impossible de contacter le serveur')
            }
        }
    }
    // set method & url to call asynchronously
    httpRequest.open('POST', 'index.php?p=anime.update&id=' + index + '&field=' + field, true)
    // build form map object to send
    var data = new FormData()
    data.append('value', value)
    httpRequest.send(data)
}

// display the new category name
function displayResult(index, field, result) {
    var element = document.querySelector('#' + field + index)
    element.innerHTML = result
}