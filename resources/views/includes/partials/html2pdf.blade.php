<script type="text/javascript" src="/js/libs/html2pdf2/jspdf.min.js"></script>
<script type="text/javascript" src="/js/libs/html2pdf2/html2canvas.min.js"></script>
<script type="text/javascript" src="/js/libs/html2pdf2/html2pdf.js"></script>
<script type="text/javascript">
// https://github.com/eKoopmans/html2pdf
$(function () {
    $("#downloadPdf").click(function () {
    	var element = document.getElementById('pdfContainer');
		html2pdf(element, {
		  margin:       1,
		  filename:     '修改意见文档.pdf',
		  image:        { type: 'jpeg', quality: 0.98 },
		  html2canvas:  { dpi: 192, letterRendering: true },
		  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
		});
    });
});
</script>