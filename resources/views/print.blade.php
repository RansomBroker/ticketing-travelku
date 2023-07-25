<html>
	<style type="text/css">
		@font-face {
	    font-family: 'Your custom font name';
	    src: url("{{ storage_path('fonts/Rubik-Regular.ttf') }}") format("truetype");
	    font-weight: 400;
	    font-style: normal;
		}

		html, body {
			margin:  0;
			padding: 0;
			font-family: 'Rubik', sans-serif;
			font-size: 12px;
		}

		.wrapper {
			width:  100%;
		}

		table {
			border-spacing: 0px;
			width: 100%;
		}

		.text-end {
			text-align: right;
		}

		td {
			padding: 10px 30px;
		}

		img {
			width: 100px;
		}

		td {
			border-bottom: 2px solid #d5d5d5;
		}

		.bg-yellow {
			background: #ffb000;
			color: black;
		}

		.bg-gray {
			background: #d5d5d5;
			color: black;
		}
	</style>
	<body>
		<div class="wrapper">
			<table>
				<tr>
					<td colspan="4">
						<div style="display: inline-block; text-align: center;">
							<div class="mb-2">
								<img src="{{ $logo }}" />
							</div>
							<strong>{{ $data->agent_name }}</strong>
						</div>
					</td>
					<td colspan="2">
						<div class="text-end">
							<h1 style="font-size: 30px; margin-bottom: 1px"><strong>INVOICE</strong></h1>
							</br>
							<strong>PT. Khaeru May Barkah (KMB Tour & Travel)</strong>
							</br>
							Jln. Galur Sari Timur No.10-A</br>
							Kel. Utan Kayu Selatan Kec. Matraman</br>
							Jakarta Timur 13120</br>
							Telp. (+6221) 2211 2737</br>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<div>
							<strong>DITUNJUKKAN KEPADA YTH</strong>
							</br>
							{{ $data->sell_to }}
						</div>
					</td>
					<td>
						<div>
							<strong>INVOICE #</strong>
							</br>
							<strong>Tanggal</strong>
						</div>
					</td>
					<td>
						<div class="text-end">
							{{ 'KMB' . '-' . substr(str_replace(['-', ':', ' '], ['', '', ''], $data->tanggal_terbit), 4) }}
							</br>
							{{ $data->tanggal_terbit }}
						</div>
					</td>
				</tr>
				<tr class="bg-yellow">
					<td colspan="3">KETERANGAN TIKET PESAWAT</td>
					<td>QTY</td>
					<td>HARGA PER PAX</td>
					<td class="text-end">JUMLAH</td>
				</tr>
				<tr>
					<td colspan="3">
						PNR: {{ $data->pnr }}
						<br>
						{!! $data->schedule !!}
					</td>
					<td>{{ $data->seat }}</td>
					<td>{{ number_format($data->sell_price, 0, ',', '.') }}</td>
					<td class="text-end">{{ number_format($data->seat * $data->sell_price, 0, ',', '.') }}</td>
				</tr>
				<tr>
					<td colspan="4" style="border-bottom: none;" rowspan="{{ 3 + count($deposit) }}">
						<div>
							<br>
							<br>
							<strong>PEMBAYARAN RESMI HANYA MELALUI</strong>
							<br>
							An. PT KHAERU MAY BERKAH
							<br>
							KCP. MATRAMAN
							<br>
							- BANK CENTRAL ASIA (BCA)
							<br>
							No. Rekening : 342-719-1985
							<br>
							- BANK MANDIRI (PERSERO)
							<br>
							No. Rekening : 006-0020-222-0000
						</div>
					</td>
					<td>Subtotal</td>
					<td class="text-end">{{ number_format($data->seat * $data->sell_price, 0, ',', '.') }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td class="text-end">{{ number_format($data->seat * $data->sell_price, 0, ',', '.') }}</td>
				</tr>
				@foreach($deposit as $item)
				<tr>
					<td>PEMBAYARAN TANGGAL {{ $item->date }}</td>
					<td class="text-end">{{ number_format($item->deposit, 0, ',', '.') }}</td>
				</tr>
				@endforeach
				<tr class="bg-gray">
					<td>JUMLAH KEKURANGAN YANG HARUS DIBAYAR</td>
					<td class="text-end">{{ number_format(($data->seat * $data->sell_price) - $total_deposit, 0, ',', '.') }}</td>
				</tr>
				<tr>
					<td colspan="6" style="border:none"><br></td>
				</tr>
				<tr>
					<td colspan="6" style="border:none">
						<strong>SYARAT DAN KETENTUAN</strong>
						<br>
						- Full payment dilakukan satu bulan sebelum keberangkatan
						<br>
						- Ketentuan booking group : Deposit / Ticketing / Materialisasi / Cancelation, menyesuaikan aturan berlaku pada masing masing Airlines.
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>