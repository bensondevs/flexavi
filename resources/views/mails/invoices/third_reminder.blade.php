Third Reminder Example

Invoice ID: {{ $invoice->id }} <br />
Invoice Number: {{ $invoice->invoice_number }} <br />
Total: {{ $invoice->total }} <br />
Total In Terms: {{ $invoice->total_in_terms }} <br />
Total Paid: {{ $invoice->total_paid }} <br />

Status: [{{ $invoice->status }}] {{ $invoice->status_description }} <br />
Payment Method: [{{ $invoice->payment_method }}] {{ $invoice->payment_method_description }} <br />