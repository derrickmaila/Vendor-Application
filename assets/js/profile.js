var oProvinces = new Object();
var oDistricts = new Object();
var oMunicipalities = new Object();

function initializeprofilepage() {
	
	oCountries = new Object();
	oCountries['Afghanistan'] = "Afghanistan";
	oCountries['Albania'] = "Albania";
	oCountries['Algeria'] = "Algeria";
	oCountries['American Samoa'] = "American Samoa";
	oCountries['Andorra'] = "Andorra";
	oCountries['Angola'] = "Angola";
	oCountries['Anguilla'] = "Anguilla";
	oCountries['Antarctica'] = "Antarctica";
	oCountries['Antigua And Barbuda'] = "Antigua And Barbuda";
	oCountries['Argentina'] = "Argentina";
	oCountries['Armenia'] = "Armenia";
	oCountries['Aruba'] = "Aruba";
	oCountries['Australia'] = "Australia";
	oCountries['Austria'] = "Austria";
	oCountries['Azerbaijan'] = "Azerbaijan";
	oCountries['Bahamas, The'] = "Bahamas, The";
	oCountries['Bahrain'] = "Bahrain";
	oCountries['Bangladesh'] = "Bangladesh";
	oCountries['Barbados'] = "Barbados";
	oCountries['Belarus'] = "Belarus";
	oCountries['Belgium'] = "Belgium";
	oCountries['Belize'] = "Belize";
	oCountries['Benin'] = "Benin";
	oCountries['Bermuda'] = "Bermuda";
	oCountries['Bhutan'] = "Bhutan";
	oCountries['Bolivia'] = "Bolivia";
	oCountries['Bosnia and Herzegovina'] = "Bosnia and Herzegovina";
	oCountries['Botswana'] = "Botswana";
	oCountries['Bouvet Island'] = "Bouvet Island";
	oCountries['Brazil'] = "Brazil";
	oCountries['British Indian Ocean Territory'] = "British Indian Ocean Territory";
	oCountries['Brunei'] = "Brunei";
	oCountries['Bulgaria'] = "Bulgaria";
	oCountries['Burkina Faso'] = "Burkina Faso";
	oCountries['Burundi'] = "Burundi";
	oCountries['Cambodia'] = "Cambodia";
	oCountries['Cameroon'] = "Cameroon";
	oCountries['Canada'] = "Canada";
	oCountries['Cape Verde'] = "Cape Verde";
	oCountries['Cayman Islands'] = "Cayman Islands";
	oCountries['Central African Republic'] = "Central African Republic";
	oCountries['Chad'] = "Chad";
	oCountries['Chile'] = "Chile";
	oCountries['China'] = "China";
	oCountries['China (Hong Kong S.A.R.)'] = "China (Hong Kong S.A.R.)";
	oCountries['China (Macau S.A.R.)'] = "China (Macau S.A.R.)";
	oCountries['Christmas Island'] = "Christmas Island";
	oCountries['Cocos (Keeling) Islands'] = "Cocos (Keeling) Islands";
	oCountries['Colombia'] = "Colombia";
	oCountries['Comoros'] = "Comoros";
	oCountries['Congo'] = "Congo";
	oCountries['Congo, Democractic Republic of the'] = "Congo, Democractic Republic of the";
	oCountries['Cook Islands'] = "Cook Islands";
	oCountries['Costa Rica'] = "Costa Rica";
	oCountries['Cote DIvoire (Ivory Coast)'] = "Cote DIvoire (Ivory Coast)";
	oCountries['Croatia (Hrvatska)'] = "Croatia (Hrvatska)";
	oCountries['Cuba'] = "Cuba";
	oCountries['Cyprus'] = "Cyprus";
	oCountries['Czech Republic'] = "Czech Republic";
	oCountries['Denmark'] = "Denmark";
	oCountries['Djibouti'] = "Djibouti";
	oCountries['Dominica'] = "Dominica";
	oCountries['Dominican Republic'] = "Dominican Republic";
	oCountries['East Timor'] = "East Timor";
	oCountries['Ecuador'] = "Ecuador";
	oCountries['Egypt'] = "Egypt";
	oCountries['El Salvador'] = "El Salvador";
	oCountries['Equatorial Guinea'] = "Equatorial Guinea";
	oCountries['Eritrea'] = "Eritrea";
	oCountries['Estonia'] = "Estonia";
	oCountries['Ethiopia'] = "Ethiopia";
	oCountries['Falkland Islands (Islas Malvinas)'] = "Falkland Islands (Islas Malvinas)";
	oCountries['Faroe Islands'] = "Faroe Islands";
	oCountries['Fiji Islands'] = "Fiji Islands";
	oCountries['Finland'] = "Finland";
	oCountries['France'] = "France";
	oCountries['French Guiana'] = "French Guiana";
	oCountries['French Polynesia'] = "French Polynesia";
	oCountries['French Southern Territories'] = "French Southern Territories";
	oCountries['Gabon'] = "Gabon";
	oCountries['Gambia, The'] = "Gambia, The";
	oCountries['Georgia'] = "Georgia";
	oCountries['Germany'] = "Germany";
	oCountries['Ghana'] = "Ghana";
	oCountries['Gibraltar'] = "Gibraltar";
	oCountries['Greece'] = "Greece";
	oCountries['Greenland'] = "Greenland";
	oCountries['Grenada'] = "Grenada";
	oCountries['Guadeloupe'] = "Guadeloupe";
	oCountries['Guam'] = "Guam";
	oCountries['Guatemala'] = "Guatemala";
	oCountries['Guinea'] = "Guinea";
	oCountries['Guinea-Bissau'] = "Guinea-Bissau";
	oCountries['Guyana'] = "Guyana";
	oCountries['Haiti'] = "Haiti";
	oCountries['Heard and McDonald Islands'] = "Heard and McDonald Islands";
	oCountries['Honduras'] = "Honduras";
	oCountries['Hungary'] = "Hungary";
	oCountries['Iceland'] = "Iceland";
	oCountries['India'] = "India";
	oCountries['Indonesia'] = "Indonesia";
	oCountries['Iran'] = "Iran";
	oCountries['Iraq'] = "Iraq";
	oCountries['Ireland'] = "Ireland";
	oCountries['Israel'] = "Israel";
	oCountries['Italy'] = "Italy";
	oCountries['Jamaica'] = "Jamaica";
	oCountries['Japan'] = "Japan";
	oCountries['Jordan'] = "Jordan";
	oCountries['Kazakhstan'] = "Kazakhstan";
	oCountries['Kenya'] = "Kenya";
	oCountries['Kiribati'] = "Kiribati";
	oCountries['Korea'] = "Korea";
	oCountries['Korea, North'] = "Korea, North";
	oCountries['Kuwait'] = "Kuwait";
	oCountries['Kyrgyzstan'] = "Kyrgyzstan";
	oCountries['Laos'] = "Laos";
	oCountries['Latvia'] = "Latvia";
	oCountries['Lebanon'] = "Lebanon";
	oCountries['Lesotho'] = "Lesotho";
	oCountries['Liberia'] = "Liberia";
	oCountries['Libya'] = "Libya";
	oCountries['Liechtenstein'] = "Liechtenstein";
	oCountries['Lithuania'] = "Lithuania";
	oCountries['Luxembourg'] = "Luxembourg";
	oCountries['Macedonia, Former Yugoslav Republic of'] = "Macedonia, Former Yugoslav Republic of";
	oCountries['Madagascar'] = "Madagascar";
	oCountries['Malawi'] = "Malawi";
	oCountries['Malaysia'] = "Malaysia";
	oCountries['Maldives'] = "Maldives";
	oCountries['Mali'] = "Mali";
	oCountries['Malta'] = "Malta";
	oCountries['Marshall Islands'] = "Marshall Islands";
	oCountries['Martinique'] = "Martinique";
	oCountries['Mauritania'] = "Mauritania";
	oCountries['Mauritius'] = "Mauritius";
	oCountries['Mayotte'] = "Mayotte";
	oCountries['Mexico'] = "Mexico";
	oCountries['Micronesia'] = "Micronesia";
	oCountries['Moldova'] = "Moldova";
	oCountries['Monaco'] = "Monaco";
	oCountries['Mongolia'] = "Mongolia";
	oCountries['Montserrat'] = "Montserrat";
	oCountries['Morocco'] = "Morocco";
	oCountries['Mozambique'] = "Mozambique";
	oCountries['Myanmar'] = "Myanmar";
	oCountries['Namibia'] = "Namibia";
	oCountries['Nauru'] = "Nauru";
	oCountries['Nepal'] = "Nepal";
	oCountries['Netherlands Antilles'] = "Netherlands Antilles";
	oCountries['Netherlands, The'] = "Netherlands, The";
	oCountries['New Caledonia'] = "New Caledonia";
	oCountries['New Zealand'] = "New Zealand";
	oCountries['Nicaragua'] = "Nicaragua";
	oCountries['Niger'] = "Niger";
	oCountries['Nigeria'] = "Nigeria";
	oCountries['Niue'] = "Niue";
	oCountries['Norfolk Island'] = "Norfolk Island";
	oCountries['Northern Mariana Islands'] = "Northern Mariana Islands";
	oCountries['Norway'] = "Norway";
	oCountries['Oman'] = "Oman";
	oCountries['Pakistan'] = "Pakistan";
	oCountries['Palau'] = "Palau";
	oCountries['Panama'] = "Panama";
	oCountries['Papua new Guinea'] = "Papua new Guinea";
	oCountries['Paraguay'] = "Paraguay";
	oCountries['Peru'] = "Peru";
	oCountries['Philippines'] = "Philippines";
	oCountries['Pitcairn Island'] = "Pitcairn Island";
	oCountries['Poland'] = "Poland";
	oCountries['Portugal'] = "Portugal";
	oCountries['Puerto Rico'] = "Puerto Rico";
	oCountries['Qatar'] = "Qatar";
	oCountries['Reunion'] = "Reunion";
	oCountries['Romania'] = "Romania";
	oCountries['Russia'] = "Russia";
	oCountries['Rwanda'] = "Rwanda";
	oCountries['Saint Helena'] = "Saint Helena";
	oCountries['Saint Kitts And Nevis'] = "Saint Kitts And Nevis";
	oCountries['Saint Lucia'] = "Saint Lucia";
	oCountries['Saint Pierre and Miquelon'] = "Saint Pierre and Miquelon";
	oCountries['Saint Vincent And The Grenadines'] = "Saint Vincent And The Grenadines";
	oCountries['Samoa'] = "Samoa";
	oCountries['San Marino'] = "San Marino";
	oCountries['Sao Tome and Principe'] = "Sao Tome and Principe";
	oCountries['Saudi Arabia'] = "Saudi Arabia";
	oCountries['Senegal'] = "Senegal";
	oCountries['Seychelles'] = "Seychelles";
	oCountries['Sierra Leone'] = "Sierra Leone";
	oCountries['Singapore'] = "Singapore";
	oCountries['Slovakia'] = "Slovakia";
	oCountries['Slovenia'] = "Slovenia";
	oCountries['Solomon Islands'] = "Solomon Islands";
	oCountries['Somalia'] = "Somalia";
	oCountries['South Africa" selected="selected'] = "South Africa";
	oCountries['South Georgia And The South Sandwich Islands'] = "South Georgia And The South Sandwich Islands";
	oCountries['Spain'] = "Spain";
	oCountries['Sri Lanka'] = "Sri Lanka";
	oCountries['Sudan'] = "Sudan";
	oCountries['Suriname'] = "Suriname";
	oCountries['Svalbard And Jan Mayen Islands'] = "Svalbard And Jan Mayen Islands";
	oCountries['Swaziland'] = "Swaziland";
	oCountries['Sweden'] = "Sweden";
	oCountries['Switzerland'] = "Switzerland";
	oCountries['Syria'] = "Syria";
	oCountries['Taiwan'] = "Taiwan";
	oCountries['Tajikistan'] = "Tajikistan";
	oCountries['Tanzania'] = "Tanzania";
	oCountries['Thailand'] = "Thailand";
	oCountries['Togo'] = "Togo";
	oCountries['Tokelau'] = "Tokelau";
	oCountries['Tonga'] = "Tonga";
	oCountries['Trinidad And Tobago'] = "Trinidad And Tobago";
	oCountries['Tunisia'] = "Tunisia";
	oCountries['Turkey'] = "Turkey";
	oCountries['Turkmenistan'] = "Turkmenistan";
	oCountries['Turks And Caicos Islands'] = "Turks And Caicos Islands";
	oCountries['Tuvalu'] = "Tuvalu";
	oCountries['Uganda'] = "Uganda";
	oCountries['Ukraine'] = "Ukraine";
	oCountries['United Arab Emirates'] = "United Arab Emirates";
	oCountries['United Kingdom'] = "United Kingdom";
	oCountries['United States'] = "United States";
	oCountries['United States Minor Outlying Islands'] = "United States Minor Outlying Islands";
	oCountries['Uruguay'] = "Uruguay";
	oCountries['Uzbekistan'] = "Uzbekistan";
	oCountries['Vanuatu'] = "Vanuatu";
	oCountries['Vatican City State (Holy See)'] = "Vatican City State (Holy See)";
	oCountries['Venezuela'] = "Venezuela";
	oCountries['Vietnam'] = "Vietnam";
	oCountries['Virgin Islands (British)'] = "Virgin Islands (British)";
	oCountries['Virgin Islands (US)'] = "Virgin Islands (US)";
	oCountries['Wallis And Futuna Islands'] = "Wallis And Futuna Islands";
	oCountries['Western Sahara'] = "Western Sahara";
	oCountries['Yemen'] = "Yemen";
	oCountries['Yugoslavia'] = "Yugoslavia";
	oCountries['Zambia'] = "Zambia";
	oCountries['Zimbabwe'] = "Zimbabwe";
	

	oProvinces['South Africa'] = new Object();
	oProvinces['South Africa']['EC'] = 'Eastern Cape';
	oProvinces['South Africa']['FS'] = 'Free State';
	oProvinces['South Africa']['GP'] = 'Gauteng';
	oProvinces['South Africa']['KZN'] = 'KwaZulu-Natal';
	oProvinces['South Africa']['LIM'] = 'Limpopo';
	oProvinces['South Africa']['MP'] = 'Mpumalanga';
	oProvinces['South Africa']['NC'] = 'Northern Cape';
	oProvinces['South Africa']['NW'] = 'North West';
	oProvinces['South Africa']['WC'] = 'Western Cape';

	oProvinces['Botswana'] = new Object();
	oProvinces['Botswana']['CEN'] = 'Central';
	oProvinces['Botswana']['GHA'] = 'Ghanzi';
	oProvinces['Botswana']['KGA'] = 'Kgalagadi';
	oProvinces['Botswana']['KGL'] = 'Kgatleng';
	oProvinces['Botswana']['KWE'] = 'Kweneng';
	oProvinces['Botswana']['NEA'] = 'North East';
	oProvinces['Botswana']['NWE'] = 'North West';
	oProvinces['Botswana']['SEA'] = 'South East';
	oProvinces['Botswana']['SOU'] = 'Southern';



	oDistricts['EC'] = new Object();
	oDistricts['EC']['DC44'] = "Alfred Nzo";
	oDistricts['EC']['DC12'] = "Amatole";
	oDistricts['EC']['DC10'] = "Cacadu";
	oDistricts['EC']['DC13'] = "Chris Hani";
	oDistricts['EC']['PortElizabeth'] = "Nelson Mandela";
	oDistricts['EC']['DC15'] = "OR Tambo";
	oDistricts['EC']['DC14'] = "Ukhahlamba";
	oDistricts['FS'] = new Object();
	oDistricts['FS']['DC18'] = "Lejweleputswa";
	oDistricts['FS']['DC17'] = "Motheo";
	oDistricts['FS']['DC20'] = "Northern Free State";
	oDistricts['FS']['DC19'] = "Thabo Mofutsanyane";
	oDistricts['FS']['DC16'] = "Xhariep";
	oDistricts['GP'] = new Object();
	oDistricts['GP']['EastRand'] = "Ekurhuleni";
	oDistricts['GP']['Johannesburg'] = "Johannesburg";
	oDistricts['GP']['CBDC2'] = "Metsweding";
	oDistricts['GP']['DC42'] = "Sedibeng";
	oDistricts['GP']['Pretoria'] = "Tshwane";
	oDistricts['GP']['CBDC8'] = "West Rand";
	oDistricts['KZN'] = new Object();
	oDistricts['KZN']['DC25'] = "Amajuba";
	oDistricts['KZN']['Durban'] = "eThekwini";
	oDistricts['KZN']['DC29'] = "iLembe";
	oDistricts['KZN']['DC43'] = "Sisonke";
	oDistricts['KZN']['DC21'] = "Ugu";
	oDistricts['KZN']['DC22'] = "Umgungundlovu";
	oDistricts['KZN']['DC27'] = "Umkhanyakude";
	oDistricts['KZN']['DC24'] = "Umzinyathi";
	oDistricts['KZN']['DC23'] = "Uthukela";
	oDistricts['KZN']['DC28'] = "uThungulu";
	oDistricts['KZN']['DC26'] = "Zululand";
	oDistricts['LIM'] = new Object();
	oDistricts['LIM']['CBDC4'] = "Bohlabela";
	oDistricts['LIM']['DC35'] = "Capricorn";
	oDistricts['LIM']['DC33'] = "Mopani";
	oDistricts['LIM']['DC34'] = "Vhembe";
	oDistricts['LIM']['DC36'] = "Waterberg";
	oDistricts['MP'] = new Object();
	oDistricts['MP']['DC32'] = "Ehlanzeni";
	oDistricts['MP']['DC30'] = "Gert Sibande";
	oDistricts['MP']['DC31'] = "Nkangala";
	oDistricts['MP']['CBDC3'] = "Sekhukhune";
	oDistricts['NW'] = new Object();
	oDistricts['NW']['DC37'] = "Bojanala Platinum";
	oDistricts['NW']['DC39'] = "Bophirima";
	oDistricts['NW']['DC38'] = "Central";
	oDistricts['NW']['CBDC1'] = "Kgalagadi";
	oDistricts['NW']['DC40'] = "Southern";
	oDistricts['NC'] = new Object();
	oDistricts['NC']['DC9'] = "Frances Baard";
	oDistricts['NC']['DC7'] = "Karoo";
	oDistricts['NC']['DC6'] = "Namakwa";
	oDistricts['NC']['DC8'] = "Siyanda";
	oDistricts['WC'] = new Object();
	oDistricts['WC']['DC2'] = "Boland";
	oDistricts['WC']['CapeTown'] = "Cape Town";
	oDistricts['WC']['DC5'] = "Central Karoo";
	oDistricts['WC']['DC4'] = "Eden";
	oDistricts['WC']['DC3'] = "Overberg";
	oDistricts['WC']['DC1'] = "West Coast";


	oMunicipalities['EC'] = new Object();
	oMunicipalities['EC']['ECDMA10'] = "Aberdeen Plain";
	oMunicipalities['EC']['EC124'] = "Amahlathi";
	oMunicipalities['EC']['EC107'] = "Baviaans";
	oMunicipalities['EC']['EC102'] = "Blue Crane Route";
	oMunicipalities['EC']['EC125'] = "Buffalo City";
	oMunicipalities['EC']['EC101'] = "Camdeboo";
	oMunicipalities['EC']['EC141'] = "Elundini";
	oMunicipalities['EC']['EC136'] = "Emalahleni";
	oMunicipalities['EC']['EC137'] = "Engcobo";
	oMunicipalities['EC']['EC144'] = "Gariep";
	oMunicipalities['EC']['EC123'] = "Great Kei";
	oMunicipalities['EC']['EC103'] = "Ikwezi";
	oMunicipalities['EC']['EC133'] = "Inkwanca";
	oMunicipalities['EC']['EC135'] = "Intsika Yethu";
	oMunicipalities['EC']['EC131'] = "Inxuba Yethemba";
	oMunicipalities['EC']['EC157'] = "King Sabata Dalindyebo";
	oMunicipalities['EC']['EC108'] = "Kouga";
	oMunicipalities['EC']['EC109'] = "Kou-Kamma";
	oMunicipalities['EC']['EC134'] = "Lukanji";
	oMunicipalities['EC']['EC104'] = "Makana";
	oMunicipalities['EC']['EC143'] = "Maletswai";
	oMunicipalities['EC']['EC121'] = "Mbhashe";
	oMunicipalities['EC']['EC151'] = "Mbizana";
	oMunicipalities['EC']['EC156'] = "Mhlontlo";
	oMunicipalities['EC']['EC122'] = "Mnquma";
	oMunicipalities['EC']['ECDMA13'] = "Mountain Zebra National Park";
	oMunicipalities['EC']['EC105'] = "Ndlambe";
	oMunicipalities['EC']['PortElizabeth'] = "Nelson Mandela";
	oMunicipalities['EC']['EC126'] = "Ngqushwa";
	oMunicipalities['EC']['EC127'] = "Nkonkobe";
	oMunicipalities['EC']['EC152'] = "Ntabankulu";
	oMunicipalities['EC']['EC128'] = "Nxuba";
	oMunicipalities['EC']['EC155'] = "Nyandeni";
	oMunicipalities['EC']['ECDMA44'] = "O'Conners Camp";
	oMunicipalities['EC']['ECDMA14'] = "Oviston Nature Reserve";
	oMunicipalities['EC']['EC154'] = "Port St Johns";
	oMunicipalities['EC']['EC153'] = "Qaukeni";
	oMunicipalities['EC']['EC138'] = "Sakhisizwe";
	oMunicipalities['EC']['EC142'] = "";
	oMunicipalities['EC']['EC106'] = "Sunday's River Valley";
	oMunicipalities['EC']['EC132'] = "Tsolwana";
	oMunicipalities['EC']['EC05b1'] = "Umzimkhulu";
	oMunicipalities['EC']['EC05b2'] = "Umzimvubu";
	oMunicipalities['FS'] = new Object();
	oMunicipalities['FS']['FS192'] = "Dihlabeng";
	oMunicipalities['FS']['FSDMA19'] = "Golden Gate Highlands National Park";
	oMunicipalities['FS']['FS162'] = "Kopanong";
	oMunicipalities['FS']['FS161'] = "Letsemeng";
	oMunicipalities['FS']['FS205'] = "Mafube";
	oMunicipalities['FS']['FS194'] = "Maluti a Phofung";
	oMunicipalities['FS']['FS172'] = "Mangaung";
	oMunicipalities['FS']['FS173'] = "Mantsopa";
	oMunicipalities['FS']['FS181'] = "Masilonyana";
	oMunicipalities['FS']['FS184'] = "Matjhabeng";
	oMunicipalities['FS']['FS204'] = "Metsimaholo";
	oMunicipalities['FS']['FS163'] = "Mohokare";
	oMunicipalities['FS']['FS201'] = "Moqhaka";
	oMunicipalities['FS']['FS185'] = "Nala";
	oMunicipalities['FS']['FS171'] = "Naledi";
	oMunicipalities['FS']['FS203'] = "Ngwathe";
	oMunicipalities['FS']['FS193'] = "Nketoana";
	oMunicipalities['FS']['FS195'] = "Phumelela";
	oMunicipalities['FS']['FS191'] = "Setsoto";
	oMunicipalities['FS']['FS182'] = "Tokologo";
	oMunicipalities['FS']['FS183'] = "Tswelopele";
	oMunicipalities['GP'] = new Object();
	oMunicipalities['GP']['Johannesburg'] = "City of Johannesburg Metro";
	oMunicipalities['GP']['Pretoria'] = "City of Tshwane Metro";
	oMunicipalities['GP']['EastRand'] = "Ekurhuleni Metro";
	oMunicipalities['GP']['GT421'] = "Emfuleni";
	oMunicipalities['GP']['GT423'] = "Lesedi";
	oMunicipalities['GP']['GT422'] = "Midvaal";
	oMunicipalities['GP']['GT411'] = "Mogale City";
	oMunicipalities['GP']['GT02b1'] = "Nokeng tsa Taemane";
	oMunicipalities['GP']['GT412'] = "Randfontein";
	oMunicipalities['GP']['GTDMA41'] = "West Rand";
	oMunicipalities['GP']['GT414'] = "Westonaria";
	oMunicipalities['KZN'] = new Object();
	oMunicipalities['KZN']['KZ263'] = "Abaqulusi";
	oMunicipalities['KZN']['KZ254'] = "Dannhauser";
	oMunicipalities['KZN']['KZ261'] = "eDumbe";
	oMunicipalities['KZN']['KZ232'] = "Emnambithi/Ladysmith";
	oMunicipalities['KZN']['KZ291'] = "eNdondakusuka";
	oMunicipalities['KZN']['KZ241'] = "Endumeni";
	oMunicipalities['KZN']['Durban'] = "Ethekwini";
	oMunicipalities['KZN']['KZ215'] = "Ezingoleni";
	oMunicipalities['KZN']['KZDMA23'] = "Gaints Castle Game Reserve";
	oMunicipalities['KZN']['KZ5a4'] = "Greater Kokstad";
	oMunicipalities['KZN']['KZ216'] = "Hibiscus Coast";
	oMunicipalities['KZN']['KZDMA22'] = "Highmoor/Kamberg Park";
	oMunicipalities['KZN']['KZ274'] = "Hlabisa";
	oMunicipalities['KZN']['KZ236'] = "Imbabazane";
	oMunicipalities['KZN']['KZ224'] = "Impendle";
	oMunicipalities['KZN']['KZ233'] = "Indaka";
	oMunicipalities['KZN']['KZ5a1'] = "Ingwe";
	oMunicipalities['KZN']['KZ272'] = "Jozini";
	oMunicipalities['KZN']['KZ5a2'] = "Kwa Sani";
	oMunicipalities['KZN']['KZ292'] = "KwaDukuza";
	oMunicipalities['KZN']['KZ294'] = "Maphumulo";
	oMunicipalities['KZN']['KZ5a3'] = "Matatiele";
	oMunicipalities['KZN']['KZ281'] = "Mbonambi";
	oMunicipalities['KZN']['KZ226'] = "Mkhambathini";
	oMunicipalities['KZN']['KZDMA43'] = "Mkhomazi Wilderness Area";
	oMunicipalities['KZN']['KZ223'] = "Mooi Mpofana";
	oMunicipalities['KZN']['KZ244'] = "Msinga";
	oMunicipalities['KZN']['KZ225'] = "Msunduzi";
	oMunicipalities['KZN']['KZ285'] = "Mthonjaneni";
	oMunicipalities['KZN']['KZ275'] = "Mtubatuba";
	oMunicipalities['KZN']['KZ293'] = "Ndwedwe";
	oMunicipalities['KZN']['KZ252'] = "Newcastle";
	oMunicipalities['KZN']['KZ286'] = "Nkandla";
	oMunicipalities['KZN']['KZ265'] = "Nongoma";
	oMunicipalities['KZN']['KZ242'] = "Nqutu";
	oMunicipalities['KZN']['KZ283'] = "Ntambanana";
	oMunicipalities['KZN']['KZ235'] = "Okhahlamba";
	oMunicipalities['KZN']['KZ227'] = "Richmond";
	oMunicipalities['KZN']['KZDMA27'] = "St Lucia Park";
	oMunicipalities['KZN']['KZ273'] = "The Big 5 False Bay";
	oMunicipalities['KZN']['KZ5a5'] = "Ubuhlebezwe";
	oMunicipalities['KZN']['KZ266'] = "Ulundi";
	oMunicipalities['KZN']['KZ212'] = "Umdoni";
	oMunicipalities['KZN']['KZ271'] = "Umhlabuyalingana";
	oMunicipalities['KZN']['KZ282'] = "uMhlathuze";
	oMunicipalities['KZN']['KZ284'] = "uMlalazi";
	oMunicipalities['KZN']['KZ222'] = "uMngeni";
	oMunicipalities['KZN']['KZ221'] = "uMshwathi";
	oMunicipalities['KZN']['KZ234'] = "Umtshezi";
	oMunicipalities['KZN']['KZ214'] = "uMuziwabantu";
	oMunicipalities['KZN']['KZ245'] = "Umvoti";
	oMunicipalities['KZN']['KZ213'] = "Umzumbe";
	oMunicipalities['KZN']['KZ262'] = "uPhongolo";
	oMunicipalities['KZN']['KZ253'] = "Utrecht";
	oMunicipalities['KZN']['KZ211'] = "Vulamehlo";
	oMunicipalities['LIM'] = new Object();
	oMunicipalities['LIM']['NP352'] = "Aganang";
	oMunicipalities['LIM']['NP334'] = "Ba-Phalaborwa";
	oMunicipalities['LIM']['NP366'] = "Bela-Bela";
	oMunicipalities['LIM']['NP351'] = "Blouberg";
	oMunicipalities['LIM']['CBLC6'] = "Bushbuckridge";
	oMunicipalities['LIM']['NP03A3'] = "Fetakgomo";
	oMunicipalities['LIM']['NP331'] = "Greater Giyani";
	oMunicipalities['LIM']['NP332'] = "Greater Letaba";
	oMunicipalities['LIM']['NP333'] = "Greater Tzaneen";
	oMunicipalities['LIM']['NP355'] = "Lepele-Nkumpi";
	oMunicipalities['LIM']['NP362'] = "Lephalale";
	oMunicipalities['LIM']['NP344'] = "Makhado";
	oMunicipalities['LIM']['NP03A2'] = "Makhuduthamaga";
	oMunicipalities['LIM']['NP04A1'] = "Maruleng";
	oMunicipalities['LIM']['NP365'] = "Modimolle";
	oMunicipalities['LIM']['NP367'] = "Mogalakwena";
	oMunicipalities['LIM']['NP353'] = "Molemole";
	oMunicipalities['LIM']['NP364'] = "Mookgopong";
	oMunicipalities['LIM']['NP341'] = "Musina";
	oMunicipalities['LIM']['NP342'] = "Mutale";
	oMunicipalities['LIM']['NP354'] = "Polokwane";
	oMunicipalities['LIM']['NP361'] = "Thabazimbi";
	oMunicipalities['LIM']['NP343'] = "Thulamela";
	oMunicipalities['MP'] = new Object();
	oMunicipalities['MP']['MP301'] = "Albert Luthuli";
	oMunicipalities['MP']['MP311'] = "Delmas";
	oMunicipalities['MP']['MP306'] = "Dipaleseng";
	oMunicipalities['MP']['MP316'] = "Dr JS Moroka";
	oMunicipalities['MP']['MP312'] = "Emalahleni";
	oMunicipalities['MP']['CBLC4'] = "Greater Groblersdal";
	oMunicipalities['MP']['CBLC3'] = "Greater Marble Hall";
	oMunicipalities['MP']['CBLC5'] = "Greater Tubatse";
	oMunicipalities['MP']['MP314'] = "Highlands";
	oMunicipalities['MP']['MP307'] = "Highveld East";
	oMunicipalities['MP']['CBDMA4'] = "Kruger Park";
	oMunicipalities['MP']['CBLC2'] = "Kungwini";
	oMunicipalities['MP']['MP305'] = "Lekwa";
	oMunicipalities['MP']['MPDMA32'] = "Lowveld";
	oMunicipalities['MP']['MP322'] = "Mbombela";
	oMunicipalities['MP']['MPDMA31'] = "Mdala Nature Reserve";
	oMunicipalities['MP']['MP313'] = "Middelburg";
	oMunicipalities['MP']['MP303'] = "Mkhondo";
	oMunicipalities['MP']['MP302'] = "Msukaligwa";
	oMunicipalities['MP']['MP324'] = "Nkomazi";
	oMunicipalities['MP']['CBDMA3'] = "Schuinsdraai Nature Reserve";
	oMunicipalities['MP']['MP304'] = "Seme";
	oMunicipalities['MP']['MP321'] = "Thaba Chweu";
	oMunicipalities['MP']['MP315'] = "Thembisile";
	oMunicipalities['MP']['MP323'] = "Umjindi";
	oMunicipalities['NW'] = new Object();
	oMunicipalities['NW']['NW403'] = "City Council of Klerksdorp";
	oMunicipalities['NW']['NW384'] = "Ditsobotla";
	oMunicipalities['NW']['NW394'] = "Greater Taung";
	oMunicipalities['NW']['NW391'] = "Kagisano";
	oMunicipalities['NW']['NW374'] = "Kgetlengrivier";
	oMunicipalities['NW']['NW396'] = "Lekwa-Teemane";
	oMunicipalities['NW']['NW372'] = "Madibeng";
	oMunicipalities['NW']['NW383'] = "Mafikeng";
	oMunicipalities['NW']['NW393'] = "Mamusa";
	oMunicipalities['NW']['NW404'] = "Maquassi Hills";
	oMunicipalities['NW']['CBLC8'] = "Merafong City";
	oMunicipalities['NW']['NW395'] = "Molopo";
	oMunicipalities['NW']['NW371'] = "Moretele";
	oMunicipalities['NW']['NW375'] = "Moses Kotane";
	oMunicipalities['NW']['NW1a1'] = "Moshaweng";
	oMunicipalities['NW']['NW392'] = "Naledi";
	oMunicipalities['NW']['NWDMA37'] = "Pilansberg National Park";
	oMunicipalities['NW']['NW402'] = "Potchefstroom";
	oMunicipalities['NW']['NW373'] = "Rustenburg";
	oMunicipalities['NW']['NW381'] = "Setla-Kgobi";
	oMunicipalities['NW']['NW382'] = "Tswaing";
	oMunicipalities['NW']['NW401'] = "Ventersdorp";
	oMunicipalities['NW']['NW385'] = "Zeerust";
	oMunicipalities['NC'] = new Object();
	oMunicipalities['NC']['NC084'] = "!Kheis";
	oMunicipalities['NC']['NC083'] = "||Khara Hais";
	oMunicipalities['NC']['NCDMA08'] = "Benede Oranje";
	oMunicipalities['NC']['NCDMA07'] = "Bo Karoo";
	oMunicipalities['NC']['NCDMA09'] = "Diamondfields";
	oMunicipalities['NC']['NC092'] = "Dikgatlong";
	oMunicipalities['NC']['NC073'] = "Emthanjeni";
	oMunicipalities['NC']['NC01B1'] = "Gamagara";
	oMunicipalities['NC']['CBLC1'] = "Ga-Segonyana";
	oMunicipalities['NC']['NC065'] = "Hantam";
	oMunicipalities['NC']['NC082'] = "Kai !Garib";
	oMunicipalities['NC']['NCDMACB1'] = "Kalahari";
	oMunicipalities['NC']['NC064'] = "Kamiesberg";
	oMunicipalities['NC']['NC074'] = "Kareeberg";
	oMunicipalities['NC']['NC066'] = "Karoo Hoogland";
	oMunicipalities['NC']['NC086'] = "Kgatelopele (NC086)";
	oMunicipalities['NC']['NC067'] = "KhÆ’i-Ma";
	oMunicipalities['NC']['NC093'] = "Magareng";
	oMunicipalities['NC']['NC081'] = "Mier";
	oMunicipalities['NC']['NC062'] = "Nama Khoi";
	oMunicipalities['NC']['NCDMA06'] = "Namaqualand";
	oMunicipalities['NC']['CBLC7'] = "Phokwane";
	oMunicipalities['NC']['NC075'] = "Renosterberg";
	oMunicipalities['NC']['NC061'] = "Richtersveld";
	oMunicipalities['NC']['NC078'] = "Siyancuma";
	oMunicipalities['NC']['NC077'] = "Siyathemba";
	oMunicipalities['NC']['NC091'] = "Sol Plaatje";
	oMunicipalities['NC']['NC076'] = "Thembelihle";
	oMunicipalities['NC']['NC085'] = "Tsantsabane";
	oMunicipalities['NC']['NC071'] = "Ubuntu";
	oMunicipalities['NC']['NC072'] = "Umsombomvu";
	oMunicipalities['WC'] = new Object();
	oMunicipalities['WC']['WC053'] = "Beaufort West";
	oMunicipalities['WC']['WC013'] = "Bergrivier";
	oMunicipalities['WC']['WCDMA02'] = "Breede River";
	oMunicipalities['WC']['WC026'] = "Breede River/Winelands";
	oMunicipalities['WC']['WC025'] = "Breede Valley";
	oMunicipalities['WC']['WC033'] = "Cape Agulhas";
	oMunicipalities['WC']['WC012'] = "Cederberg";
	oMunicipalities['WC']['WCDMA05'] = "Central Karoo";
	oMunicipalities['WC']['CapeTown'] = "City of Cape Town";
	oMunicipalities['WC']['WC023'] = "Drakenstein";
	oMunicipalities['WC']['WC044'] = "George";
	oMunicipalities['WC']['WC041'] = "Kannaland";
	oMunicipalities['WC']['WC048'] = "Knysna";
	oMunicipalities['WC']['WC051'] = "Laingsburg";
	oMunicipalities['WC']['WC042'] = "Langeberg";
	oMunicipalities['WC']['WC011'] = "Matzikama";
	oMunicipalities['WC']['WC043'] = "Mossel Bay";
	oMunicipalities['WC']['WC045'] = "Oudtshoorn";
	oMunicipalities['WC']['WCDMA03'] = "Overberg";
	oMunicipalities['WC']['WC032'] = "Overstrand";
	oMunicipalities['WC']['WC047'] = "Plettenberg Bay";
	oMunicipalities['WC']['WC052'] = "Prince Albert";
	oMunicipalities['WC']['WC014'] = "Saldanha Bay";
	oMunicipalities['WC']['WCDMA04'] = "South Cape";
	oMunicipalities['WC']['WC024'] = "Stellenbosch";
	oMunicipalities['WC']['WC015'] = "Swartland";
	oMunicipalities['WC']['WC034'] = "Swellendam";
	oMunicipalities['WC']['WC031'] = "Theewaterskloof";
	oMunicipalities['WC']['WCDMA01'] = "West Coast";
	oMunicipalities['WC']['WC022'] = "Witzenberg";


}
