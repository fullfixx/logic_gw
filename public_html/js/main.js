
let filter = function () {
    let searchInput = document.getElementById('filter-input');

    searchInput.addEventListener('keyup', function () {
        let searchValue = searchInput.value.toLowerCase(),
            filterElements = document.querySelectorAll('#filter-list tr');

        filterElements.forEach(item => {
            if (item.innerHTML.toLowerCase().indexOf(searchValue) > -1) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        })

    })

};

filter();



function filterTo(data) {
    filterElements = document.querySelectorAll('#filter-list tr');
    filterElements.forEach(item => {
        if (item.innerHTML.toLowerCase().indexOf(data) > -1) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    })
};


$('td.status-color').each(function(){
    var x = $(this).text();
    if (x == 'готов') $(this).css({background: 'rgba(134,213,149,0.8)'});
    if (x == 'приостановлен') $(this).css({background: '#d58686'});
    if (x == 'в работе') $(this).css({background: '#86bcd5'});
});
$('td.statusclarity-color').each(function(){
    var y = $(this).text();
    if (y == 'доработка') $(this).css({background: '#ff7171'});
    if (y == 'завершен') $(this).css({background: '#86bcd5'});
});

// $(document).ready(function () {
//     $('#notice').modal('show');
// });

let notice = document.getElementById("notice");
let span = document.getElementsByClassName("close-notice")[0];
span.onclick = function () {
    notice.style.display = "none";
}
window.onclick = function (event) {
    if (event.target == notice) {
        notice.style.display = "none";
    }
}


