function converttopdf() {
  var pdf = new jsPDF('l', 'pt', 'a4');

  // source can be HTML-formatted string, or a reference
  // to an actual DOM element from which the text will be scraped.
  source = $('#report_form_div .content2')[0];

  // we support special element handlers. Register them with jQuery-style 
  // ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
  // There is no support for any other type of selectors 
  // (class, of compound) at this time.
  specialElementHandlers = {
      // element with id of "bypass" - jQuery style selector
      '#bypassme' : function(element, renderer) {
          // true = "handled elsewhere, bypass text extraction"
          return true
        }
      };
      margins = {
        top : 50,
        bottom : 50,
        left : 30,
        width : 522
      };
  // all coords and widths are in jsPDF instance's declared units
  // 'inches' in this case
  pdf.fromHTML(source, // HTML string or DOM elem ref.
  margins.left, // x coord
  margins.top, { // y coord
      'width' : margins.width, // max width of content on PDF
      'elementHandlers' : specialElementHandlers,

    },

    function(dispose) {
      // dispose: object with X, Y of the last line add to the PDF 
      //          this allow the insertion of new lines after html
      pdf.save('Report.pdf');
    }, margins);
}

function demoPDF() {
  var options = {
    background: '#fff',
    pagesplit: true, 
    // width: 522
};
margins = {
        top : 80,
        bottom : 60,
        left : 60,
        width : 522
      };
var pdf = new jsPDF('p', 'pt', 'ledger');
pdf.addHTML($('#report_form_div')[0],0,0, options, function () {
pdf.save('Test.pdf');
// HideLoader();
}, margins);
}
