<!-- <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet"> -->

<style>
    @font-face {
        font-family: 'Calibri';
        src: url('assets/fonts/Calibri.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'Calibri';
        src: url('assets/fonts/calibrib.ttf') format('truetype');
        font-weight: bold;
        font-style: normal;
    }

    @font-face {
        font-family: 'KrutiDev';
        src: url('assets/fonts/KrutiDev.ttf') format('truetype');
        /* Replace with actual font path */
        font-weight: normal;
        font-style: normal;
    }

    @font-face {
        font-family: 'KrutiDev';
        src: url('assets/fonts/Kruti Dev 010 Bold.ttf') format('truetype');
        /* Replace with actual font path */
        font-weight: bold;
        font-style: normal;
    }

    @font-face {
        font-family: 'Nirmala UI';
        src: url('assets/fonts/Nirmala.ttf') format('truetype');
        /* Path to the regular font */
        font-weight: normal;
        font-style: normal;
    }

    /* Dompdf page box – no margins */
    @page {
        margin: 0cm;
        padding: 0;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
        font-size: 1rem;
        font-weight: 400;
        color: #111;
        background-color: #fff;
        -webkit-text-size-adjust: 100%;
        -webkit-tap-highlight-color: transparent;
    }

    table {
        caption-side: bottom;
        border-collapse: collapse;
    }

    td,
    th {
        border: 1px solid #111 !important;
        vertical-align: middle !important;
    }

    .table {
        font-family: Calibri, sans-serif !important;
    }

    .table td,
    .table th {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
    }

    .table tbody td {
        line-height: 0.8 !important;
    }

    .table th {
        line-height: 0.7 !important;
    }

    .footer-total {
        vertical-align: text-top !important;
        font-size: 10pt !important;
    }

    .fontCalibri {
        font-family: 'Calibri', sans-serif !important;
    }

    .fontkrutidev {
        font-family: 'KrutiDev', sans-serif !important;
    }

    .d-flex {
        display: flex !important;
        width: 100%;
        flex-direction: row;
    }

    .text-start {
        text-align: start !important;
    }

    .text-end {
        text-align: end !important;
    }

    .text-center {
        text-align: center !important;
    }

    .border {
        border: 1px solid #111 !important;
    }

    /* ---------- A4 Transcript Layout ---------- */

    .transcript-container {
        width: 21cm;
        height: auto;
        min-height: 25cm;
        padding: 0cm 2cm 1.3cm 2cm;
        position: relative;
        box-sizing: border-box;
        margin: 0 auto;
    }

    .transcript-container {
        page-break-inside: avoid;
    }


    .doc-container {
        width: 21cm;
        height: auto;
        padding: 0.8cm 2cm 1.3cm 2cm;
        position: relative;
        box-sizing: border-box;
        margin: 0 auto;
    }

    .doc-container {
        page-break-inside: avoid;
    }


    .marksheet {
        margin-top: 3.4cm;
    }

    /* Signature text at bottom-right of each student's LAST page */
    .coe-sign {
        position: absolute;
        right: 7cm;
        bottom: 2cm;
        font-weight: bold;
        font-size: 11pt;
        font-family: "Times New Roman", Times, serif !important;
    }

    /* just for preview container in browser */
    #scrollableView {
        width: 21cm !important;
        margin: 0 auto;
    }

    @media print {
        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        .marksheet table,
        .marksheet tr,
        .marksheet td,
        .marksheet th {
            page-break-inside: avoid;
        }
    }

    p {
        margin: 0;
        text-align: justify;
    }
</style>