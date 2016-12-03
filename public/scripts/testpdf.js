/* scripts/app.js */
    

$(function () {

    var specialElementHandlers = {
        '#editor': function (element,renderer) {
            return true;
        }
    };
 $('#cmd').click(function () {
        var doc = new jsPDF();
        doc.fromHTML($('#crap').html(), 15, 15, {
            'width': 2400,'elementHandlers': specialElementHandlers
        });
        doc.save('sample-file.pdf');
    });  
})();
