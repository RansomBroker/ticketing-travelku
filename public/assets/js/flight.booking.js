const parseDate = (date) => {
	const month = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AGU', 'SEP', 'OCT', 'NOV', 'DES'];
	let s = date.split('T');
	date = s[0].split('-')
	const time = s[1].substring(0, 5)

	return `${date[2]} ${month[parseInt(date[1])]} ${time}`
}

const formatIdr = (numb) => {
	numb = Math.ceil(numb)
	const format = numb.toString().split('').reverse().join('');
	const convert = format.match(/\d{1,3}/g);
	const rupiah = 'IDR ' + convert.join('.').split('').reverse().join('')
	return rupiah;
}

const displayResult = (item) => {
	item.dictionaries = JSON.parse(atob(dictionaries))
	let resultHtml = ``
	resultHtml += `
		<div class="card-result card h-100" key="${item.id}">
			<div class="card-body">
				<div class="row gy-3 gx-2 align-items-center">
					<div class="col-lg-2">
						<div class="d-flex align-items-center">
							<div class="detail ms-2">
								<h6 class="p-0 m-0 fw-bold">${item.dictionaries.carriers[item.itineraries[0].segments[0].carrierCode]}</h6>
								<p style="font-size: 14px" class="text-muted p-0 m-0"><small>${item.itineraries[0].segments[0].carrierCode}-${item.itineraries[0].segments[0].number}</small></p>
							</div>
						</div>
					</div>
					<div class="col-lg-7">
						<div class="d-flex align-items-center justify-content-between">
							<div class="departure text-end me-3">
								<div class="badge bg-info mb-2"><small>${item.itineraries[0].segments[0].departure.iataCode}</small></div>
								<p class="m-0"><small>${parseDate(item.itineraries[0].segments[0].departure.at)}</small></p>
							</div>
							<div class="divider divider-info flex-grow-1">
								<div class="divider-text"><i class='bx bxs-plane-take-off text-info'></i></div>
							</div>
							<div class="arrival ms-3">
								<div class="badge bg-info mb-2"><small>${item.itineraries[item.itineraries.length - 1].segments[item.itineraries[item.itineraries.length - 1].segments.length - 1].arrival.iataCode}</small></div>
								<p class="m-0"><small>${parseDate(item.itineraries[item.itineraries.length - 1].segments[item.itineraries[item.itineraries.length - 1].segments.length - 1].arrival.at)}</small></p>
							</div>
						</div>
					</div>
					<div class="col-lg-3 text-start text-lg-end">
						<h5 class="fw-bold text-info m-0 mb-2">${formatIdr(total)}</h5>
					</div>
				</div>
				<div id="segment-display" key="${item.id}" class="segment-display show row gy-3 gax-2 align-items-center py-2">`
	
	item.itineraries.forEach((itinerary) => {
		itinerary.segments.forEach((segment, index) => {

			resultHtml += `
				<div class="col-12 mt-4">
					<div class="d-flex align-items-stretch">
						<div class="line position-relative me-2">
							<div class="rounded-circle bg-info position-absolute top-0" style="width: 10px; height: 10px"></div>
							<div class="bg-info ms-1" style="height: 100%; width: 1px"></div>
							<div class="rounded-circle bg-info position-absolute bottom-0" style="width: 10px; height: 10px"></div>
						</div>
						<div class="detail px-2 flex-grow-1">
							<div class="origin">
								<p style="font-size: 12px" class="lead m-0">${segment.departure.iataCode}</p>
								<p style="font-size: 12px" class="m-0">${parseDate(segment.departure.at)}</p>
							</div>
							<div class="w-100 p-3 border bg-light my-2 rounded">
								<div class="d-flex align-items-center">
									<div class="img">
										<div style="width: 50px; height: 50px;" class="bg-label-info d-flex justify-content-center align-items-center rounded-circle">
											<img style="width: 30px; height: 30px;" class="rounded-circle" src="${logoUrl}/${segment.carrierCode}.png">
										</div>
									</div>
									<div class="detail ms-2">
										<p class="fw-bold m-0">${item.dictionaries.carriers[segment.carrierCode]}</p>
										<p style="font-size: 11px" class="m-0">${segment.carrierCode} - ${segment.number} ${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weight ? `<i class='bx bx-briefcase-alt-2 ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[index].includedCheckedBags.weight}${item.travelerPricings[0].fareDetailsBySegment[index].includedCheckedBags.weightUnit}` : ''} <i class='bx bx-info-circle ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[index].class} (${item.travelerPricings[0].fareDetailsBySegment[index].fareBasis})</p>
									</div>
								</div>
							</div>
							<div class="origin">
								<p style="font-size: 12px" class="lead m-0">${segment.arrival.iataCode}</p>
								<p style="font-size: 12px" class="m-0">${parseDate(segment.arrival.at)}</p>
							</div>
						</div>
					</div>
					<div class="alert alert-info my-3">Stop and changes plane in next arrival</div>
				</div>
			`

		})
	})

	resultHtml +=	`
				</div>
			</div>
		</div>
	`

	$('#flight-detail').html(resultHtml)
}

const buildForm = (data, isDomestic) => {
	offer = JSON.parse(atob(offer))
	offer.dictionaries = JSON.parse(atob(dictionaries))
	offer.total = total
	offer = btoa(JSON.stringify(offer))
	let passengerHtml = ``
	data.travelerPricings.forEach((passenger) => {
		passengerHtml += `
			<input type="hidden" name="offer" value="${offer}">
			<input type="hidden" name="total" value="${total}">
			<h6 class="my-3">PASSENGER ${passenger.travelerId} (${passenger.travelerType})</h6>
				<div class="row gx-3 gy-3 mb-2">
					<div class="col-lg-2">
						<select class="form-select" name="tl_${passenger.travelerId}" required>
							<option value="Mr.">Mr.</option>
							<option value="Ms.">Ms.</option>
							<option value="Mrs.">Mrs.</option>
							<option value="Miss.">Miss.</option>
							<option value="Dr.">Dr.</option>
						</select>
					</div>
					<div class="col-lg-5 form-group">
						<input placeholder="First Name" class="form-control" name="fn_${passenger.travelerId}" type="text" required>
					</div>
					<div class="col-lg-5 form-group">
						<input placeholder="Last Name" class="form-control" name="ln_${passenger.travelerId}" type="text" required>
					</div>
					<div class="col-lg-6">
						<select class="form-select" name="gn_${passenger.travelerId}" required>
							<option value="MALE">Male</option>
							<option value="FEMALE">Female</option>
						</select>
					</div>
					<div class="col-lg-6 form-group">
						<input placeholder="Date of Birth" ${passenger.travelerType == 'ADULT' ? `max="${minBirthDay}"` : `` }  class="form-control" name="db_${passenger.travelerId}" type="date" required>
					</div>
					<div class="col-lg-12 form-group">
						<input placeholder="Country" class="form-control" name="ct_${passenger.travelerId}" type="text" required>
					</div>`
		
		if(!isDomestic && passenger.travelerType == 'ADULT') {
			passengerHtml += `
				<div class="col-lg-12 form-group">
					<input placeholder="Passport Number" class="form-control" name="pn_${passenger.travelerId}" type="text" required>
				</div>
				<div class="col-lg-6 form-group">
					<input maxlength="2" placeholder="Issuing Country (eg: ID)" class="form-control" name="cc_${passenger.travelerId}" pattern="[A-Z]{2}" type="text" required>
				</div>
				<div class="col-lg-6 form-group">
					<input placeholder="Expiry Date" min="${minExpirationDate}" class="form-control" name="ex_${passenger.travelerId}" type="date" required>
				</div>
			`
		}

		passengerHtml += `
			</div>
		`
	})

	passengerHtml += `</form>`

	$("#passenger-form").html(passengerHtml)

	$("#form-pay").submit(async (e) => {
		e.preventDefault()
		console.log(document.querySelector('#form-pay'));
		let formData = new FormData(document.querySelector('#form-pay'));
		const response = await fetch(apiSnap, {
			method: 'POST',
			body: formData
		})

		const data = await response.json()
		
		snap.pay(data.snap_token, {
      onSuccess: function(result){
				window.location.href = endURL + '/' + result.order_id
      },

      onPending: function(result){
				window.location.href = endURL + '/' + result.order_id
      },

      onError: function(result){
				window.location.href = endURL + '/' + result.order_id
      }
    });
	})
}

const fetchOffer = async () => {
	const response = await fetch(apiURL, {
		method: 'post',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: 'offer=' + offer
	})

	const result = await response.json()

	console.log(result)

	if(result.hasOwnProperty('errors')) {
		result.errors.forEach((error) => {
			const toastPlacementExample = document.querySelector('.toast-placement-ex')
			toastPlacementExample.innerHTML = `
				<div class="toast-header">
			    <i class="bx bx-bell me-2"></i>
			    <div class="me-auto fw-semibold">${error.title}</div>
			    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
			  </div>
			  <div class="toast-body">${error.message}</div>
			`
			toastPlacement = new bootstrap.Toast(toastPlacementExample)
			toastPlacement.show()

			setTimeout(() => {
				window.location.href = baseURL
			}, 5500)
		})

		return false;
	}

	$("#overlay").css({'opacity': '0'})

	setTimeout(() => {
		$("#overlay").css({'visibility': 'hidden'})
	}, 1000)

	if(result.dictionaries.locations[result.data.flightOffers[0].itineraries[0].segments[0].departure.iataCode].countryCode == result.dictionaries.locations[result.data.flightOffers[0].itineraries[0].segments[result.data.flightOffers[0].itineraries[0].segments.length - 1].arrival.iataCode].countryCode) {
		buildForm(result.data.flightOffers[0], true)
	} else {
		buildForm(result.data.flightOffers[0], false)
	}

	displayResult(result.data.flightOffers[0])
}

fetchOffer();