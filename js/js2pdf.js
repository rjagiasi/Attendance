function converttopdf() {
  var pdf = new jsPDF('p', 'pt', 'ledger');
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
        top : 80,
        bottom : 60,
        left : 60,
        width : 522
      };
  // all coords and widths are in jsPDF instance's declared units
  // 'inches' in this case
  pdf.fromHTML(source, // HTML string or DOM elem ref.
  margins.left, // x coord
  margins.top, { // y coord
      'width' : margins.width, // max width of content on PDF
      'elementHandlers' : specialElementHandlers
    },

    function(dispose) {
      // dispose: object with X, Y of the last line add to the PDF 
      //          this allow the insertion of new lines after html
      pdf.save('Report.pdf');
    }, margins);
}

function demoPDF() {
  var doc = new jsPDF('p', 'pt');
  doc.text("From HTML", 40, 50);
  var res = doc.autoTableHtmlToJson(document.getElementById("abc"));
  doc.autoTable(res.columns, res.data, {startY: 60});
  return doc;
}
