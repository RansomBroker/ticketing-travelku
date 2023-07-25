const slideShow = () => {
	let imageIndex = 0;
	window.setInterval(() => {
		console.log(imageIndex)
		const array = ['aqsa.png', 'makkah.jpg', 'borobudur.jpeg']
		
    imageIndex++;

		if(imageIndex >= 3) {
			imageIndex = 0
		}

		console.log(slideShowUrl + '/' + array[imageIndex])
		
		$("#slideshow").fadeOut(1000, function() {
        $("#slideshow").attr("src", slideShowUrl + '/' + array[imageIndex]);
    }).fadeIn(1000);
	}, 2500)
}

$("#switch-airport").click((e) => {
	e.preventDefault();
	const origin = $(`[name=origin]`).val()
	const originPlaceholder = $(`[name=origin-placeholder]`).val()
	const destination = $(`[name=destination]`).val()
	const destinationPlaceholder = $(`[name=destination-placeholder]`).val()

	$(`[name=origin]`).val(destination)
	$(`[name=origin-placeholder]`).val(destinationPlaceholder)
	$(`[name=destination]`).val(origin)
	$(`[name=destination-placeholder]`).val(originPlaceholder)
})

const increment = (e) => {
	e.preventDefault()
	let adultval = parseInt($('[name=adult]').val())
	let childval = parseInt($('[name=children]').val())
	let infantval = parseInt($('[name=infant]').val())
	const total = adultval + childval + infantval

	const target = e.currentTarget.getAttribute('target');
	let value = $(`[name=${target}]`).val();
	value++;

	if(target == 'adult')
	{
		if(value > 1)
		{
			$('.decrement[target=adult]').prop('disabled', false)
			$('.increment[target=infant]').prop('disabled', false)
		}
	}

	if(target == 'infant')
	{
		if(value >= adultval)
		{
			$('.increment[target=infant]').prop('disabled', true)
		}
	}

	if(total >= 8)
	{
		$('.increment').prop('disabled', true)
	}

	if(value > 0)
	{
		$(`.decrement[target=${target}]`).prop('disabled', false)
	}

	$(`[name=${target}]`).val(value);
	$(`#counter-${target}`).html(value);

	adultval = parseInt($('[name=adult]').val())
	childval = parseInt($('[name=children]').val())
	infantval = parseInt($('[name=infant]').val())
	$(`#input-passenger`).val(`${adultval + childval + infantval} Passenger`)
}

const decrement = (e) => {
	e.preventDefault()
	let adultval = parseInt($('[name=adult]').val())
	let childval = parseInt($('[name=children]').val())
	let infantval = parseInt($('[name=infant]').val())
	const total = adultval + childval + infantval

	const target = e.currentTarget.getAttribute('target');
	let value = $(`[name=${target}]`).val();
	value--;

	if(target == 'adult')
	{
		if(value < 1)
		{
			e.currentTarget.setAttribute('disabled', true)
			$('.increment').prop('disabled', false)
			$('.increment[target=infant]').prop('disabled', true)
			return false
		}

		if(infantval == adultval)
		{
			$(`[name=infant]`).val(value);
			$(`#counter-infant`).html(value);
			$('.increment[target=infant]').prop('disabled', true)
			$(`[name=${target}]`).val(value);
			$(`#counter-${target}`).html(value);
			return false
		}
	}

	if(infantval == adultval)
	{
		$('.increment[target=infant]').prop('disabled', true)
		$(`[name=${target}]`).val(value);
		$(`#counter-${target}`).html(value);
		if(value >= 1) return false
	}

	if(value < 1)
	{
		$(`.decrement[target=${target}]`).prop('disabled', true)
	}

	if(total <= 9)
	{
		$('.increment').prop('disabled', false)
	}

	$(`[name=${target}]`).val(value);
	$(`#counter-${target}`).html(value);

	adultval = parseInt($('[name=adult]').val())
	childval = parseInt($('[name=children]').val())
	infantval = parseInt($('[name=infant]').val())
	$(`#input-passenger`).val(`${adultval + childval + infantval} Passenger`)
}

const showSelector = () => {
	$("#passenger-selector #selector").css('visibility', 'visible');
}

const hideSelector = (e) => {
	e.preventDefault()
	$("#passenger-selector #selector").css('visibility', 'hidden');	
}

$(".return-switch").click(() => {
	if($('[name=return]')[0].classList.contains('invisible')) {
		$('[name=return]')[0].classList.remove('invisible')	
		$('[name=return]')[0].classList.remove('d-none')
		$('[name=return]').prop('required', true)
	} else {
		$('[name=return]')[0].classList.add('invisible')
		$('[name=return]')[0].classList.add('d-none')	
		$('[name=return]').prop('required', false)
		$('[name=return]').val('')
	}
})

const searchAirport = async (e, target) => {
	const url = apiURL
	const response = await fetch(`${url}/${e.currentTarget.value}`)
	const data = await response.json()
	let html = ``
	
	if(data.length == 0)
	{
		if(!$(`.${target}-picker`).hasClass('invisible')) {
			$(`.${target}-picker`).addClass('invisible')
		}
	} else {
		if($(`.${target}-picker`).hasClass('invisible')) {
			$(`.${target}-picker`).removeClass('invisible')
		}
	}

	data.forEach((item) => {
		html += `
		<li iata="${item.iata}" text="${item.city}, ${item.country} (${item.iata})">
			<h6 class="mb-1">${item.city}, ${item.country}</h6>
			<p class="m-0 text-muted text-truncate">${item.iata} - ${item.name}</h6>
		</li>
		`
	})

	$(`.${target}-picker ul`).html(html)

	$(`.${target}-picker li`).click((e) => {
		const iata = e.currentTarget.getAttribute('iata')
		const text = e.currentTarget.getAttribute('text')
		$(`[name=${target}]`).val(iata)
		$(`[name=${target}-placeholder]`).val(text)
		$(`.${target}-picker`).addClass('invisible');
	})
}

$(".increment").click((e) => increment(e))
$(".decrement").click((e) => decrement(e))
$("#input-passenger").click(showSelector)
$("#done-passenger").click((e) => hideSelector(e))

$("[name=origin-placeholder]").blur(() => {
	$("[name=origin-placeholder]").val('Jakarta, Indonesia (CGK)')
	$("[name=origin]").val('CGK')
	setTimeout(() => {
		$(".origin-picker[target=origin]").addClass('invisible');
	}, 500)
})

$("[name=destination-placeholder]").blur(() => {
	$("[name=destination-placeholder]").val('Jeddah, Saudi Arabia (JED)')
	$("[name=destination]").val('JED')
	setTimeout(() => {
		$(".destination-picker[target=destination]").addClass('invisible');
	}, 500)
})

$("[name=origin-placeholder]").keyup((e) => searchAirport(e, 'origin'))
$("[name=destination-placeholder]").keyup((e) => searchAirport(e, 'destination'))

$("[name=departure]").change(() => {
	const val = $("[name=departure]").val()
	let date = new Date(val)
	date.setDate(date.getDate() + 1);
	$("[name=return]").attr('min', date.toISOString().split('T')[0])
})

slideShow()