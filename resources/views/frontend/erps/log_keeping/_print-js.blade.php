// Define custom CSS styles for the printed table
    const customStyles = `
      <style>
        table {
          width: 100%;
          border-collapse: collapse;
          border-spacing: 0;
          border: 1px solid #ddd; /* Add border */
          border-radius: 5px; /* Add border radius */
        }
        th, td {
          padding: 8px;
          text-align: left;
          border-bottom: 1px solid #ddd; /* Add border to table cells */
        }
        th {
          background-color: #f2f2f2; /* Add background color to header cells */
        }
      </style>
    `;

    printWindow.document.write("<html><head><title>@yield('title')</title>");
    printWindow.document.write(customStyles); // Apply custom styles
    printWindow.document.write("</head><body >");
    printWindow.document.write(printContent.outerHTML);
    printWindow.document.write("</body></html>");
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
