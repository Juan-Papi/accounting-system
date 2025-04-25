<div>

    @if (count($invoices) > 0)

        <div class="bg-gray-800 rounded shadow-lg py-6 px-8 mt-11">
            <table class="w-full">
                <thead>
                    <tr class="text-gray-50">
                        <th class="w-1/2 px-4 py-2 text-left">Fecha</th>
                        <th class="w-1/4 px-4 py-2 text-left">Total</th>
                        <th class="w-1/4 px-4 py-2 text-left">Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $invoice)
                        <tr class="text-gray-50">
                            <td class="px-4 py-2">{{ $invoice->date()->toFormattedDateString() }}</td>
                            <td class="px-4 py-2">{{ $invoice->total() }}</td>
                            <td class="px-4 py-2"><a class="btn btn-blue"
                                    href="/user/invoice/{{ $invoice->id }}">Download</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endif

</div>
