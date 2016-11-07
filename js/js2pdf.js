function converttopdfold(noofcol) {
	
	var layout;
	if(noofcol <= 9)
		layout = 'p';
	else
		layout = 'l';

	var pdf = new jsPDF(layout, 'pt', 'a4');
	// pdf.cellInitialize();
	// pdf.setFontSize(10);
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
  	width : 600
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

function converttopdf (noofcol) {

	var layout;
	if(noofcol < 7)
		layout = 'p';
	else
		layout = 'l';

	var source = $('#report_form_div .content2')[0];
	var columns = [];
	var headers = $(source).find("th");
	$.each(headers, function(index, el) {
		columns.push($(el).html());
	});

	var data = $(source).find("td");
	var rows = [];
	var n = headers.length, j=0;
	var studrow = [];

	$.each(data, function(index, el) {

		var val = $(el).html();
		if(val.includes(":"))
			val = val.split(":")[1] + "%";

		studrow.push(val);

		if(j==n-1)
		{
			rows.push(studrow);
			studrow = [];
		}

		j = (j+1)%n;
	});

    var doc = new jsPDF(layout, 'pt');
    doc.autoTable(columns, rows);
    doc.save("Report.pdf");
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
