$(function() {

	$('.icon-menu').click(function() {
			$('.side_nav').toggleClass('open');
	});

});

var flattenObject = function(ob) {
	var toReturn = {};
  var dateKeys = ["createdAt", "updatedAt"];
	for (var i in ob) {

		if (i === 'relationships') continue;
		if (!ob.hasOwnProperty(i)) continue;

		if ((typeof ob[i]) == 'object') {
			var flatObject = flattenObject(ob[i]);
			for (var x in flatObject) {
				if (!flatObject.hasOwnProperty(x)) continue;
        if (dateKeys.indexOf(x) != -1) {
          toReturn[x] = new Date(flatObject[x] * 1000);
        } else {
          toReturn[x] = flatObject[x];
        }
			}
		} else {
			toReturn[i] = ob[i];
		}
	}
	return toReturn;
}

function fileSizeLabel(limit) {
	return Math.round(limit / 1024 / 1024) + 'MB';
}

function uniqueString() {
	var text     = "";
	var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

	for (var i = 0; i < 12; i++) {
		text += possible.charAt(Math.floor(Math.random() * possible.length));
	}
	return text;
}

function createDateTimeSelectors(prefix, date, hidden) {

	return createDateSelectors(prefix, date, hidden) + createTimeSelectors(prefix, date);

}

function createDateSelectors(prefix, date, hidden) {

	hidden = typeof hidden !== 'undefined' ? hidden : false;

	if (date === undefined) {
		date = new Date();
	}

	date = moment(date);

	var hideStr = hidden ? 'style="display:none;"' : '';

	// MONTHS
	var listStr = '<select id="'+prefix+'TimeMonth" name="'+prefix+'TimeMonth" '+hideStr+'>';

	for (var i = 0; i <= 11; i++) {
		listStr += '<option value="' + i + '" ';
		if (i + 1 == date.format('M')) { listStr += 'SELECTED'; }
		listStr += '>' +  moment({ month: i }).format('MMM') + '</option>';
	}

	listStr += '</select>';

	// DAYS
	listStr += '<select id="'+prefix+'TimeDay" name="'+prefix+'TimeDay" '+hideStr+'>';

	for (var i = 1; i <= 31; i++) {
		listStr += '<option value="' + i  + '" ';
		if (i == date.format('D')) { listStr += 'SELECTED'; }
		listStr += '>' +  moment({ day: i }).format('D') + '</option>';
	}

	listStr += '</select>';

	// YEARS
	listStr += '<select id="'+prefix+'TimeYear" name="'+prefix+'TimeYear" '+hideStr+'>';

	var thisYear = moment().format('YYYY');
	var twoYears = moment().add(2, 'years').format('YYYY');

	for (var i = thisYear; i <= twoYears; i++) {
		listStr += '<option value="' + i + '" ';
		if (i == date.format('YYYY')) { listStr += 'SELECTED'; }
		listStr += '>' +  moment({ year: i }).format('YYYY') + '</option>';
	}

	listStr += '</select>';

	if (!hidden) {
		listStr += '<br/><div class="clear"></div>';
	}

	return listStr;

}

function createTimeSelectors(prefix, date) {

	if (date === undefined) {
		date = new Date();
	}

	date = moment(date);

	// HOURS
	var listStr = '<select id="'+prefix+'TimeHour" name="'+prefix+'TimeHour">';

	for (var i = 0; i <= 11; i++) {
		listStr += '<option value="' + (i % 12) + '" ';
		if (i == date.format('h')) { listStr += 'SELECTED'; }
		listStr += '>' +  moment({ hour: i }).format('h') + '</option>';
	}

	listStr += '</select>';

	// MINUTES
	listStr += '<select id="'+prefix+'TimeMinute" name="'+prefix+'TimeMinute">';

	for (var i = 0; i <= 55; i = i + 5) {
		listStr += '<option value="' + i + '" ';
		if (i == date.format('mm')) { listStr += 'SELECTED'; }
		listStr += '>' +  moment({ minute: i }).format('mm') + '</option>';
	}

	listStr += '</select>';

	// AM / PM
	listStr += '<select id="'+prefix+'TimePm" name="'+prefix+'TimePm">';

	listStr += '<option value="12" ';
	if ('pm' == date.format('a')) { listStr += 'SELECTED'; }
	listStr += '>PM</option>';

	listStr += '<option value="0" ';
	if ('am' == date.format('a')) { listStr += 'SELECTED'; }
	listStr += '>AM</option>';

	listStr += '</select>';

	return listStr;

}

function camelize(str) {
  return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
    if (+match === 0) return "";
    return index == 0 ? match.toLowerCase() : match.toUpperCase();
  });
}
