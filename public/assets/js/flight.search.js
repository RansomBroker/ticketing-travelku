let businessMargin = 0;
let margin = 0;
let domesticMargin = 0;

const fetchModule = async () => {
	const response = await fetch(apiModule)
	const data = await response.json()
	businessMargin = data.businessMargin
	margin = data.margin
	domesticMargin = data.domesticMargin
}

const parseData = `origin=${origin}&destination=${destination}&departure=${departure}&return=${returns}&class=${clases}&adult=${adult}&children=${children}&infant=${infant}`

$("#toggle-filter").click(() => {
	if($("#filter-box").hasClass('show')) return $("#filter-box").removeClass('show')
	$("#filter-box").addClass('show')
})

const parseDate = (date, hideDate = false) => {
	const month = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AGU', 'SEP', 'OCT', 'NOV', 'DES'];
	let s = date.split('T');
	date = s[0].split('-')
	const time = s[1].substring(0, 5)
	if(hideDate)
		return `${time}`
	return `${date[2]} ${month[parseInt(date[1])]} ${time}`
}

const formatIdr = (numb) => {
	numb = Math.ceil(numb)
	const format = numb.toString().split('').reverse().join('');
	const convert = format.match(/\d{1,3}/g);
	const rupiah = 'IDR ' + convert.join('.').split('').reverse().join('')
	return rupiah;
}

const parsePrice = (numb) => {
	numb = Math.ceil(numb)
	let originC = originCountry.split(', ')
	originC = originC[1].split(' (')
	originC = originC[0]

	let destinationC = destinationCountry.split(', ')
	destinationC = destinationC[1].split(' (')
	destinationC = destinationC[0]

	if(clases == "BUSINESS")
	{
		if(businessMargin.includes('%'))
			return numb + (numb * parseInt(businessMargin.replace('%', '')) / 100)

		return numb + parseInt(businessMargin.replace('.', ''))
	}

	if(originC == destinationC) {
		if(domesticMargin.includes('%'))
			return numb + (numb * parseInt(domesticMargin.replace('%', '')) / 100)

		return numb + parseInt(domesticMargin.replace('.', ''))
	}

	if(originC != destinationC) {
		if(margin.includes('%'))
			return numb + (numb * parseInt(margin.replace('%', '')) / 100)

		return numb + parseInt(margin.replace('.', ''))	
	}
}

const searchFlight = async () => {
	await fetchModule()

	const url = api

	const response = await fetch(url, {
		method: 'post',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded'
		},
		body: parseData
	})

	const result = await response.json()

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
				window.location.href = base
			}, 5500)
		})

		return false;
	}

	if(result.data.length == 0) {
		const toastPlacementExample = document.querySelector('.toast-placement-ex')
		toastPlacementExample.innerHTML = `
			<div class="toast-header">
		    <i class="bx bx-bell me-2"></i>
		    <div class="me-auto fw-semibold">NOT FOUND</div>
		    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
		  </div>
		  <div class="toast-body">Flight with your criteria is not found</div>
		`
		toastPlacement = new bootstrap.Toast(toastPlacementExample)
		toastPlacement.show()

		setTimeout(() => {
			window.location.href = base
		}, 5500)

		return false;
	}

	let html = ``

	for(var k in result.dictionaries.carriers) {
		html += `<li class="me-3 mb-3"><input class="me-1 form-check-input airline-filter" type="checkbox" data="${k}" name="airline_filter"> <img style="width: 20px" class="me-3" src="${logoUrl}/${k}.png" alt="${k}"/>${result.dictionaries.carriers[k]}</li>`
	}

	$('#list-airline').html(html)
	displayResult(result)
	$("#overlay").css({'opacity': '0'})

	setTimeout(() => {
		$("#overlay").css({'visibility': 'hidden'})
	}, 1000)

	setFilter(result);
}

const setFilter = (result) => {
	result.data.forEach((item, index) => {
		let stops = 0;
		item.itineraries.forEach((itinerary) => {
			stops += itinerary.segments.length - 1
		})
		result.data[index].stops = stops;
	})

	$("[name=stops]").change((e) => {
		const checked = document.querySelectorAll('[name=airline_filter]:checked')
		let newResult = {};
		let airlines = []
		const priceVal = document.querySelector('[name=price-filter]').value;

		newResult.data = result.data.filter((item) => Math.ceil(parsePrice(parseInt(item.price.total))) <= parseInt(priceVal))
		
		if(e.currentTarget.value != '-') {
			newResult.data = newResult.data.filter((item) => item.stops == e.currentTarget.value)
		}

		if(checked.length > 0) {
			checked.forEach((elem) => {
				airlines.push(elem.getAttribute('data'))
			})

			newResult.data = newResult.data.filter((item) => airlines.includes(item.itineraries[0].segments[0].carrierCode))
		}

		newResult.dictionaries = result.dictionaries
		newResult.meta = result.meta

		displayResult(newResult)
	})

	$("[name=price-filter]").change((e) => {
		$("#max-holder").html(`(Max: ${formatIdr(e.currentTarget.value)})`)
		const stopsVal = $("[name=stops]").val()
		const checked = document.querySelectorAll('[name=airline_filter]:checked')
		let newResult = {};
		let airlines = []

		newResult.data = result.data.filter((item) => Math.ceil(parsePrice(parseInt(item.price.total))) <= parseInt(e.currentTarget.value))

		if(stopsVal != '-') {
			newResult.data = newResult.data.filter((item) => item.stops == stopsVal)
		}

		if(checked.length > 0) {
			checked.forEach((elem) => {
				airlines.push(elem.getAttribute('data'))
			})

			newResult.data = newResult.data.filter((item) => airlines.includes(item.itineraries[0].segments[0].carrierCode))
		}

		newResult.dictionaries = result.dictionaries
		newResult.meta = result.meta
		displayResult(newResult)
	})

	$(".airline-filter").change(() => {
		const checked = document.querySelectorAll('[name=airline_filter]:checked')
		let newResult = {}
		let airlines = []

		const priceVal = document.querySelector('[name=price-filter]').value;
		newResult.data = result.data.filter((item) => Math.ceil(parsePrice(parseInt(item.price.total))) <= parseInt(priceVal))

		const stopsVal = $("[name=stops]").val()
		if(stopsVal != '-') {
			newResult.data = newResult.data.filter((item) => item.stops == stopsVal)
		}

		if(checked.length > 0) {
			checked.forEach((elem) => {
				airlines.push(elem.getAttribute('data'))
			})

			newResult.data = newResult.data.filter((item) => airlines.includes(item.itineraries[0].segments[0].carrierCode))
		}

		newResult.dictionaries = result.dictionaries
		newResult.meta = result.meta
		displayResult(newResult)
	})
}

const displayResult = (result) => {
	let resultHtml = ``


	if(screen.width > 992) {
		result.data.forEach( async (item) => {
			resultHtml += `
				<div class="card-result card mb-3" key="${item.id}">
					<div class="card-body">
						<div class="row gy-3 gx-2 align-items-center">
							<div class="col-lg-3">
								<div class="d-flex align-items-center">
									<div style="width: 50px; height: 50px;" class="bg-label-info d-flex justify-content-center align-items-center rounded-circle">
										<img style="width: 30px; height: 30px;" class="rounded-circle" src="${logoUrl}/${item.itineraries[0].segments[0].carrierCode}.png">
									</div>
									<div class="detail ms-2">
										<h6 class="p-0 m-0 fw-bold">${result.dictionaries.carriers[item.itineraries[0].segments[0].carrierCode]}</h6>
										<p class="text-muted p-0 m-0"><small>${item.itineraries[0].segments[0].carrierCode}-${item.itineraries[0].segments[0].number} ${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weight ? `<i class='bx bx-briefcase-alt-2 ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weight}${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weightUnit}` : `` }  <i class='bx bx-info-circle ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[0].class} (${item.travelerPricings[0].fareDetailsBySegment[0].fareBasis})</small></p>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
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
								<h5 class="fw-bold text-info m-0 mb-2">${formatIdr(parsePrice(item.price.total))}</h5>
								<form action="${bookingUrl}" method="post">
									<input type="hidden" name="dictionaries" value="${btoa(JSON.stringify(result.dictionaries))}">
									<input type="hidden" name="offer" value="${btoa(JSON.stringify(item))}">
									<input type="hidden" name="total" value="${parsePrice(item.price.total)}">
									<input type="hidden" name="base" value="${parsePrice(item.price.base)}">
									<input type="hidden" name="tax" value="${parsePrice(item.price.total) - parsePrice(item.price.base)}">
									<button class="btn btn-info">Choose</button>
								</form>
							</div>
						</div>
						<div id="segment-display" key="${item.id}" class="segment-display row gy-3 gax-2 align-items-center py-2">`
			
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
												<p class="fw-bold m-0">${result.dictionaries.carriers[segment.carrierCode]}</p>
												<p style="font-size: 11px" class="m-0">${segment.carrierCode} - ${segment.number} ${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weight ? `<i class='bx bx-briefcase-alt-2 ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weight}${item.travelerPricings[0].fareDetailsBySegment[0].includedCheckedBags.weightUnit}` : `` } <i class='bx bx-info-circle ms-2'></i> ${item.travelerPricings[0].fareDetailsBySegment[index].class} (${item.travelerPricings[0].fareDetailsBySegment[index].fareBasis})</p>
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
		})
		$('#result-container').html(resultHtml)
		$('.card-result').click((e) => {
			const key = e.currentTarget.getAttribute('key')
			if($(".segment-display[key=" + key + "]").hasClass('show'))
			{
				$(".segment-display[key=" + key + "]").removeClass('show')	
			}
			else
			{
				$(".segment-display[key=" + key + "]").addClass('show')	
			}
		})
	} else {
		result.data.forEach((item, index) => {
			let stops = 0;
			item.itineraries.forEach((itinerary) => {
				stops += itinerary.segments.length - 1
			})
			result.data[index].stops = stops;
		})

		console.log(result)

		result.data.forEach(async (item) => {
			resultHtml += `
				<div class="card-result card mb-3" key="${item.id}">
					<div class="card-body px-3 py-3">
						<div class="row m-0">
							<div class="col-6 p-0">
								<div class="d-flex align-items-center justify-content-between">
									<div class="departure-detail">
										<p class="m-0 mb-3" style="font-size: 12px"><small>${parseDate(item.itineraries[0].segments[0].departure.at, true)}</small></p>
										<div class="badge bg-info" style="font-size: 10px"><small>${item.itineraries[item.itineraries.length - 1].segments[item.itineraries[item.itineraries.length - 1].segments.length - 1].arrival.iataCode}</small></div>
									</div>
									<div class="misc text-center flex-grow-1 relative mx-2">
										<p class="m-0" style="font-size: 10px"><small>${item.itineraries[0].duration}</small></p>
										<div class="divider divider-info m-0 w-100" style="left:0">
											<div class="divider-text"><i class='bx bxs-plane-take-off text-info'></i></div>
										</div>
										<p class="m-0" style="font-size: 10px"><small>${item.stops} stop</small></p>
									</div>
									<div class="departure-detail">
										<p class="m-0 mb-3" style="font-size: 12px"><small>${parseDate(item.itineraries[item.itineraries.length - 1].segments[item.itineraries[item.itineraries.length - 1].segments.length - 1].arrival.at, true)}</small></p>
										<div class="badge bg-info " style="font-size: 10px"><small>${item.itineraries[item.itineraries.length - 1].segments[item.itineraries[item.itineraries.length - 1].segments.length - 1].arrival.iataCode}</small></div>
									</div>
								</div>
							</div>
							<div class="col-6 p-0 text-end">
								<h5 style="font-size:14px" class="fw-bold text-info m-0 mb-2">${formatIdr(parsePrice(item.price.total))}</h5>
							</div>
							<div class="col-12 p-0 mt-2">
								<div class="d-flex align-items-center">
									<img style="width: 20px; height: 20px;" src="${logoUrl}/${item.itineraries[0].segments[0].carrierCode}.png">
									<h6 style="font-size: 11px" class="p-0 m-0 fw-bold ms-2">${result.dictionaries.carriers[item.itineraries[0].segments[0].carrierCode]}</h6>
								</div>
							</div>
						</div>
					</div>
				</div>
			`
		})

		$('#result-container').html(resultHtml)
	}

}

searchFlight()