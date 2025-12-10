
<link rel="stylesheet" href="<?php echo base_url('assets/handsometable/');?>handsontable.full.min.css" />
<script type="text/javascript" src="<?php echo base_url('assets/handsometable/');?>handsontable.full.min.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/');?>xlsx.full.min.js"></script>
<script type="text/javascript">
	
        // Excel Export
        function exportToExcel(title_excel) {
            title_excel = title_excel+".xlsx";
            const ws = XLSX.utils.json_to_sheet(tableData);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Report");
            XLSX.writeFile(wb, title_excel);
        }
</script>