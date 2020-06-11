jQuery(document).ready(function($) {
    var promptSave = false;

    load_rules();
    refresh_settings();

    $(window).on('beforeunload', function(e) {
        if (promptSave) {
            return 'Your rules has been modified. Leave this page without save the changes?';
        }
    });

    $('input[name="submit"]').on('click', function() {
        promptSave = false;
    });

    $('select').on('change', function() {
        promptSave = true;
    });

    $('input[name=lookup_mode]').on('change', function() {
        refresh_settings();
    });

    $('#download').on('click', function(e) {
        e.preventDefault();

        if ($('#database_name').val().length == 0 || $('#token').val().length == 0) {
            $('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Please make sure you have entered the login crendential.</p></div>');
            return;
        }

        $('#download_status').html('');
        $('#database_name,#token,#download').prop('disabled', true);
        $('#ip2location-download-progress').show();

        $.post(ajaxurl, {
            action: 'update_ip2location_redirection_database',
            database: $('#database_name').val(),
            token: $('#token').val()
        }, function(response) {
            if (response == 'SUCCESS') {
                alert('Download completed.');

                $('#download_status').html('<div id="message" class="updated"><p>Successfully downloaded the ' + $('#database_name :selected').text() + ' BIN database. Please refresh information by <a href="javascript:;" id="reload">reloading</a> the page.</p></div>');

                $('#reload').on('click', function() {
                    window.location = window.location.href.split('#')[0];
                });
            } else {
                alert(response);

                $('#download_status').html('<div id="message" class="error"><p><strong>ERROR</strong>: Failed to download ' + $('#database_name :selected').text() + ' BIN database. Please make sure you correctly enter the login crendential.</p></div>');
            }
        }).always(function() {
            $('#database_name').val('');
            $('#database_name,#token,#download').prop('disabled', false);
            $('#ip2location-download-progress').hide();
        });
    });

    function insert_rule(country_codes, from, to, url_from, url_to, http_code) {
        var countries = {
            "": "",
            "AF": "Afghanistan",
            "AL": "Albania",
            "DZ": "Algeria",
            "AS": "American Samoa",
            "AD": "Andorra",
            "AO": "Angola",
            "AI": "Anguilla",
            "AQ": "Antarctica",
            "AG": "Antigua And Barbuda",
            "AR": "Argentina",
            "AM": "Armenia",
            "AW": "Aruba",
            "AU": "Australia",
            "AT": "Austria",
            "AZ": "Azerbaijan",
            "BS": "Bahamas",
            "BH": "Bahrain",
            "BD": "Bangladesh",
            "BB": "Barbados",
            "BY": "Belarus",
            "BE": "Belgium",
            "BZ": "Belize",
            "BJ": "Benin",
            "BM": "Bermuda",
            "BT": "Bhutan",
            "BO": "Bolivia, Plurinational State Of",
            "BQ": "Bonaire, Sint Eustatius And Saba",
            "BA": "Bosnia And Herzegovina",
            "BW": "Botswana",
            "BV": "Bouvet Island",
            "BR": "Brazil",
            "IO": "British Indian Ocean Territory",
            "BN": "Brunei Darussalam",
            "BG": "Bulgaria",
            "BF": "Burkina Faso",
            "BI": "Burundi",
            "KH": "Cambodia",
            "CM": "Cameroon",
            "CA": "Canada",
            "CV": "Cape Verde",
            "KY": "Cayman Islands",
            "CF": "Central African Republic",
            "TD": "Chad",
            "CL": "Chile",
            "CN": "China",
            "CX": "Christmas Island",
            "CC": "Cocos (keeling) Islands",
            "CO": "Colombia",
            "KM": "Comoros",
            "CG": "Congo",
            "CD": "Congo, The Democratic Republic Of The",
            "CK": "Cook Islands",
            "CR": "Costa Rica",
            "HR": "Croatia",
            "CU": "Cuba",
            "CW": "Cura\u00c7ao",
            "CY": "Cyprus",
            "CZ": "Czech Republic",
            "CI": "C\u00d4te D\'Ivoire",
            "DK": "Denmark",
            "DJ": "Djibouti",
            "DM": "Dominica",
            "DO": "Dominican Republic",
            "EC": "Ecuador",
            "EG": "Egypt",
            "SV": "El Salvador",
            "GQ": "Equatorial Guinea",
            "ER": "Eritrea",
            "EE": "Estonia",
            "ET": "Ethiopia",
            "FK": "Falkland Islands (malvinas)",
            "FO": "Faroe Islands",
            "FJ": "Fiji",
            "FI": "Finland",
            "FR": "France",
            "GF": "French Guiana",
            "PF": "French Polynesia",
            "TF": "French Southern Territories",
            "GA": "Gabon",
            "GM": "Gambia",
            "GE": "Georgia",
            "DE": "Germany",
            "GH": "Ghana",
            "GI": "Gibraltar",
            "GR": "Greece",
            "GL": "Greenland",
            "GD": "Grenada",
            "GP": "Guadeloupe",
            "GU": "Guam",
            "GT": "Guatemala",
            "GG": "Guernsey",
            "GN": "Guinea",
            "GW": "Guinea-bissau",
            "GY": "Guyana",
            "HT": "Haiti",
            "HM": "Heard Island And Mcdonald Islands",
            "VA": "Holy See (vatican City State)",
            "HN": "Honduras",
            "HK": "Hong Kong",
            "HU": "Hungary",
            "IS": "Iceland",
            "IN": "India",
            "ID": "Indonesia",
            "IR": "Iran, Islamic Republic Of",
            "IQ": "Iraq",
            "IE": "Ireland",
            "IM": "Isle Of Man",
            "IL": "Israel",
            "IT": "Italy",
            "JM": "Jamaica",
            "JP": "Japan",
            "JE": "Jersey",
            "JO": "Jordan",
            "KZ": "Kazakhstan",
            "KE": "Kenya",
            "KI": "Kiribati",
            "KP": "Korea, Democratic People\'s Republic Of",
            "KR": "Korea, Republic Of",
            "KW": "Kuwait",
            "KG": "Kyrgyzstan",
            "LA": "Lao People\'s Democratic Republic",
            "LV": "Latvia",
            "LB": "Lebanon",
            "LS": "Lesotho",
            "LR": "Liberia",
            "LY": "Libya",
            "LI": "Liechtenstein",
            "LT": "Lithuania",
            "LU": "Luxembourg",
            "MO": "Macao",
            "MK": "Macedonia, The Former Yugoslav Republic Of",
            "MG": "Madagascar",
            "MW": "Malawi",
            "MY": "Malaysia",
            "MV": "Maldives",
            "ML": "Mali",
            "MT": "Malta",
            "MH": "Marshall Islands",
            "MQ": "Martinique",
            "MR": "Mauritania",
            "MU": "Mauritius",
            "YT": "Mayotte",
            "MX": "Mexico",
            "FM": "Micronesia, Federated States Of",
            "MD": "Moldova, Republic Of",
            "MC": "Monaco",
            "MN": "Mongolia",
            "ME": "Montenegro",
            "MS": "Montserrat",
            "MA": "Morocco",
            "MZ": "Mozambique",
            "MM": "Myanmar",
            "NA": "Namibia",
            "NR": "Nauru",
            "NP": "Nepal",
            "NL": "Netherlands",
            "NC": "New Caledonia",
            "NZ": "New Zealand",
            "NI": "Nicaragua",
            "NE": "Niger",
            "NG": "Nigeria",
            "NU": "Niue",
            "NF": "Norfolk Island",
            "MP": "Northern Mariana Islands",
            "NO": "Norway",
            "OM": "Oman",
            "PK": "Pakistan",
            "PW": "Palau",
            "PS": "Palestinian Territory, Occupied",
            "PA": "Panama",
            "PG": "Papua New Guinea",
            "PY": "Paraguay",
            "PE": "Peru",
            "PH": "Philippines",
            "PN": "Pitcairn",
            "PL": "Poland",
            "PT": "Portugal",
            "PR": "Puerto Rico",
            "QA": "Qatar",
            "RO": "Romania",
            "RU": "Russian Federation",
            "RW": "Rwanda",
            "RE": "R\u00c9union",
            "BL": "Saint Barth\u00c9lemy",
            "SH": "Saint Helena, Ascension And Tristan Da Cunha",
            "KN": "Saint Kitts And Nevis",
            "LC": "Saint Lucia",
            "MF": "Saint Martin (french Part)",
            "PM": "Saint Pierre And Miquelon",
            "VC": "Saint Vincent And The Grenadines",
            "WS": "Samoa",
            "SM": "San Marino",
            "ST": "Sao Tome And Principe",
            "SA": "Saudi Arabia",
            "SN": "Senegal",
            "RS": "Serbia",
            "SC": "Seychelles",
            "SL": "Sierra Leone",
            "SG": "Singapore",
            "SX": "Sint Maarten (dutch Part)",
            "SK": "Slovakia",
            "SI": "Slovenia",
            "SB": "Solomon Islands",
            "SO": "Somalia",
            "ZA": "South Africa",
            "GS": "South Georgia And The South Sandwich Islands",
            "SS": "South Sudan",
            "ES": "Spain",
            "LK": "Sri Lanka",
            "SD": "Sudan",
            "SR": "Suriname",
            "SJ": "Svalbard And Jan Mayen",
            "SZ": "Swaziland",
            "SE": "Sweden",
            "CH": "Switzerland",
            "SY": "Syrian Arab Republic",
            "TW": "Taiwan, Province Of China",
            "TJ": "Tajikistan",
            "TZ": "Tanzania, United Republic Of",
            "TH": "Thailand",
            "TL": "Timor-leste",
            "TG": "Togo",
            "TK": "Tokelau",
            "TO": "Tonga",
            "TT": "Trinidad And Tobago",
            "TN": "Tunisia",
            "TR": "Turkey",
            "TM": "Turkmenistan",
            "TC": "Turks And Caicos Islands",
            "TV": "Tuvalu",
            "UG": "Uganda",
            "UA": "Ukraine",
            "AE": "United Arab Emirates",
            "GB": "United Kingdom",
            "US": "United States",
            "UM": "United States Minor Outlying Islands",
            "UY": "Uruguay",
            "UZ": "Uzbekistan",
            "VU": "Vanuatu",
            "VE": "Venezuela, Bolivarian Republic Of",
            "VN": "Viet Nam",
            "VG": "Virgin Islands, British",
            "VI": "Virgin Islands, U.s.",
            "WF": "Wallis And Futuna",
            "EH": "Western Sahara",
            "YE": "Yemen",
            "ZM": "Zambia",
            "ZW": "Zimbabwe",
            "AX": "\u00c5land Islands"
        };

        var http_codes = {
            "301": "301 Permanently Redirect",
            "302": "302 Temporary Redirect"
        };
        var exclude = false;
        var keep_query = false;

        var page_list = '<optgroup label="Pages">';
        $.each(pages, function(i, row) {
            page_list += '<option value="' + row.page_id + '">' + row.page_title + '</option>';
        });
        page_list += '</optgroup>';

        var post_list = '<optgroup label="Posts">';
        $.each(posts, function(i, row) {
            post_list += '<option value="' + row.post_id + '">' + row.post_title + '</option>';
        });
        post_list += '</optgroup>';

        var $country_list = $('<select data-placeholder="Choose Country..." class="chosen" multiple>').on('change', function() {
            $(this).parent().find('.country-codes').remove();
            $(this).parent().find('.country_codes').val($(this).val().filter(function(s) {
                return (s != '');
            }).join(';'));
        });

		var codes = country_codes.split(';');

		$.each(codes, function(i, code) {
			if (code.substr(0, 1) == '-') {
				 exclude = true;
				 codes[i] = code.substr(1);
			}
		});

        $.each(countries, function(iso_code, country_name) {
            $country_list.append('<option value="' + iso_code + '"' + ((codes.indexOf(iso_code) != -1) ? ' selected' : '') + '>' + country_name + '</option>');
        });

        var $exclude_input = $('<input type="hidden" name="exclude[]" class="exclude-input" value="' + (exclude ? '1' : '0') + '">');

        var $exclude_checkbox = $('<input type="checkbox" class="exclude-checkbox"' + ((exclude) ? ' checked' : '') + '>');
        $exclude_checkbox.on('change', function() {
            $(this).parent().find('.exclude-input').val(($(this).is(':checked')) ? 1 : 0);
        });

        var $from_list = $('<select name="from[]" data-placeholder="Choose Page..." class="chosen redirect-from">')
            .on('chosen:ready', function() {
                $(this).attr('data-prev', $(this).val());
            })
            .on('chosen:showing_dropdown', function() {
                $(this).attr('data-prev', $(this).val());
            })
            .on('change', function() {
                if ($(this).val() != 'url' && $(this).val() != 'domain' && $(this).val() == $(this).parent().next().children().val()) {
                    alert('The value of [From] and [Destination] cannot be same.');
                    $(this).val('');
                    $('.chosen').trigger('chosen:updated');
                    return;
                }

                if ($(this).attr('data-prev') == 'domain') {
                    $(this).parent().parent().find('.redirect-to').val('');
                    $(this).parent().parent().find('.domain-container').slideUp('fast');
                    $('.chosen').trigger('chosen:updated');
                }

				if ($(this).val() == 'url') {
					$(this).parent().find('.url-container').slideDown();
                    $(this).parent().find('.domain-container').slideUp('fast');

				} else if ($(this).val() == 'domain') {
                    $(this).parent().parent().find('.domain-container').slideDown();
                    $(this).parent().parent().find('.url-container').slideUp('fast');

					$(this).parent().parent().find('.redirect-to').val('domain');
                    $('.chosen').trigger('chosen:updated');

                } else {
					 $(this).parent().find('.url-container').slideUp('fast');
                     $(this).parent().find('.domain-container').slideUp('fast');
                }
            })
            .append('<option value=""></option>')
            .append('<option value="any"> [Any Page]</option>')
            .append('<option value="home"> [Home Page]</option>')
            .append('<option value="url"> [Enter URL]</option>')
            .append('<option value="domain"> [Enter Domain]</option>')
            .append(page_list)
            .append(post_list);

        var $destination_list = $('<select name="to[]" data-placeholder="Choose Destination..." class="chosen redirect-to">')
            .on('chosen:ready', function() {
                $(this).attr('data-prev', $(this).val());
            })
            .on('chosen:showing_dropdown', function() {
                $(this).attr('data-prev', $(this).val());
            })
            .on('change', function() {
                if ($(this).val() != 'url' && $(this).val() != 'domain' && $(this).val() == $(this).parent().prev().children().val()) {
                    alert('The value of [From] and [Destination] cannot be same.');
                    $(this).val('');
                    $('.chosen').trigger('chosen:updated');
                    return;
                }

                if ($(this).attr('data-prev') == 'domain') {
                    $(this).parent().parent().find('.redirect-from').val('');
                    $(this).parent().parent().find('.domain-container').slideUp('fast');
                    $('.chosen').trigger('chosen:updated');
                }

				if ($(this).val() == 'url') {
					$(this).parent().find('.url-container').slideDown();
					$(this).parent().find('.domain-container').slideUp('fast');

				} else if ($(this).val() == 'domain') {
                    $(this).parent().parent().find('.domain-container').slideDown();
                    $(this).parent().parent().find('.url-container').slideUp('fast');

					$(this).parent().parent().find('.redirect-from').val('domain');
                    $('.chosen').trigger('chosen:updated');

                } else {
                    $(this).parent().find('.url-container').slideUp('fast');
					$(this).parent().find('.domain-container').slideUp('fast');
                }
            })
            .append('<option value=""></option>')
            .append('<option value="url"> [Enter URL]</option>')
            .append('<option value="domain"> [Enter Domain]</option>')
            .append(page_list)
            .append(post_list);

        var $http_code_list = $('<select name="status_code[]"  data-placeholder="Choose Redirection..." class="chosen http-code">');

        $.each(http_codes, function(code, code_name) {
            $http_code_list.append('<option value="' + code + '"' + ((code == http_code) ? ' selected' : '') + '>' + code_name + '</option>');
        });

        $from_list.find('option[value="' + from + '"]').attr('selected', '');
        $destination_list.find('option[value="' + to + '"]').attr('selected', '');

        if (from == 'domain') {
            if (url_from.substr(0, 1) == '*') {
                keep_query = true;
                url_from = url_from.substr(1);
            }
        }

        var $rule = $('<tr>')
            .append($('<td>').append($country_list).append('<input type="hidden" name="country_codes[]" value="' + country_codes + '" class="country_codes">').append($('<p />').append($('<label />').append($exclude_checkbox).append($exclude_input).append(' Redirect all countries <strong>except</strong> country listed above.'))))
            .append($('<td>').append($from_list).append('<div class="url-container" style="display:' + ((from == 'url') ? 'block' : 'none') + '"><input type="text" name="url_from[]" value="' + url_from + '" class="url regular-text" maxlength="255" /></div>').append('<div class="domain-container" style="display:' + ((from == 'domain') ? 'block' : 'none') + '"><input type="text" name="domain_from[]" value="' + url_from + '" class="domain regular-text" maxlength="255" /><label><input type="checkbox" name="keep_query[]"' + ((keep_query) ? ' checked' : '') + ' /> Keep query string.</label></div>'))
            .append($('<td>').append($destination_list).append('<div class="url-container" style="display:' + ((to == 'url') ? 'block' : 'none') + '"><input type="text" name="url_to[]" value="' + url_to + '" class="url regular-text" maxlength="255" /></div>').append('<div class="domain-container" style="display:' + ((to == 'domain') ? 'block' : 'none') + '"><input type="text" name="domain_to[]" value="' + url_to + '" class="domain regular-text" maxlength="255" /></div>'))
            .append($('<td>').append($http_code_list))
            .append($('<td>').append('<a href="javascript:;" class="button-rule-action"></a>'));

        $('#rules').append($rule);

        $('.chosen').chosen({
            disable_search_threshold: 5,
            width: 240,
        });

        $('#rules .button-rule-action').attr('class', 'button-rule-action button-remove-rule').off('click').on('click', function(e) {
            e.preventDefault();
            $(this).parent().parent().remove();
            promptSave = true;
        });

        $('#rules tr:last-child').find('.button-rule-action').attr('class', 'button-rule-action button-add-rule').off('click').on('click', function(e) {
            e.preventDefault();
            insert_rule('', '', '', '', '', 301);
        });
    }

    function load_rules() {
        if (typeof(rules) !== 'undefined') {
            $.each(rules, function(i, row) {
                if (row.length == 5) {
                    insert_rule(row[0], row[1], row[2], '', row[3], row[4]);
                } else {
                    insert_rule(row[0], row[1], row[2], row[3], row[4], row[5]);
                }
            });
            insert_rule('', '', '', '', '', 301);
        }
    }

    function refresh_settings() {
        if ($('#lookup_mode_bin').is(':checked')) {
            $('#bin_database').show();
            $('#ws_access').hide();
        } else {
            $('#bin_database').hide();
            $('#ws_access').show();
        }
    }
});