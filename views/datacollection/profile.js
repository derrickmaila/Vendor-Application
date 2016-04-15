function initializeprofilepage() {

	var cCountry = document.getElementById('regcountry').value;

	cCountry = cCountry.toLowerCase();

/*
	if ( $('#lNobee').val() ) {

		hidebee();
	}
*/
	else if ( cCountry.indexOf('south') == -1
			&& cCountry.indexOf('africa') == -1 ) {

		hidebee();
	}

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

/*
	oDtiratingagencynames['BVA086'] = 'Abacus Verification (Pty) Ltd';
	oDtiratingagencynames['BVA073'] = 'ABC for BEE (Pty) Ltd';
	oDtiratingagencynames['BVA114'] = 'Amavula BEE Verification CC';
	oDtiratingagencynames['BVA068'] = 'Apala VZR Verification Agency';
	oDtiratingagencynames['BVA021'] = 'AQRate (Pty) Ltd';
	oDtiratingagencynames['BVA102'] = 'AQRate Gauteng (Pty) Ltd';
	oDtiratingagencynames['BVA019'] = 'AQRate KZN (Pty) Ltd';
	oDtiratingagencynames['BVA092'] = 'Ardent Business Partners (Pty) Ltd';
	oDtiratingagencynames['BVA110'] = 'Aspigon 91 t/a HR Planning Inc';
	oDtiratingagencynames['BVA053'] = 'B9 Control Solutions CC';
	oDtiratingagencynames['BVA074'] = 'BBBEE Rating Agency';
	oDtiratingagencynames['BVA050'] = 'BEE - Matrix Cc';
	oDtiratingagencynames['BVA040'] = 'BEE BIZ Compliance (Pty) Ltd';
	oDtiratingagencynames['BVA130'] = 'BEE Certification Advisors (Pty) Ltd t/a BEE Certification';
	oDtiratingagencynames['BVA057'] = 'BEE Empowered and Labour Consultancy CC';
	oDtiratingagencynames['BVA038'] = 'BEE Online (Pty) Ltd';
	oDtiratingagencynames['BVA044'] = 'BEE Professional Assignments CC';
	oDtiratingagencynames['BVA149'] = 'BEE Rated Verification Agency CC';
	oDtiratingagencynames['BVA049'] = 'BEE Rating Solutions (Pty) Ltd';
	oDtiratingagencynames['BVA014'] = 'BEE Verification Agency CC';
	oDtiratingagencynames['BVA115'] = 'Beescore (Pty) Ltd';
	oDtiratingagencynames['BVA031'] = 'BEESCORE (Pty) Ltd';
	oDtiratingagencynames['BVA026'] = 'Black Economic Empowerment Verification Agency t/a Beever Agency';
	oDtiratingagencynames['BVA071'] = 'Brentwood Kolisa (Pty) Ltd';
	oDtiratingagencynames['BVA023'] = 'Client King CC';
	oDtiratingagencynames['BVA065'] = 'Codex Ratings (Pty) Ltd';
	oDtiratingagencynames['BVA056'] = 'DRGSiyaya (Pty) Ltd';
	oDtiratingagencynames['BVA037'] = 'Emex Trust';
	oDtiratingagencynames['BVA030'] = 'Empowerdex (Pty) Ltd';
	oDtiratingagencynames['BVA141'] = 'Empowerdex (Pty) Ltd';
	oDtiratingagencynames['BVA101'] = 'Empowerdex Northern Regions (Pty) Ltd';
	oDtiratingagencynames['BVA142'] = 'Empowerdex Verification Services KZN (Pty) Ltd';
	oDtiratingagencynames['BVA018'] = 'EmpowerLogic (Pty) Ltd';
	oDtiratingagencynames['BVA064'] = 'Empoweryst CC';
	oDtiratingagencynames['BVA096'] = 'Ernst & Young B-BBEE Verification Agency (Pty) Ltd';
	oDtiratingagencynames['BVA041'] = 'Grant Thorton';
	oDtiratingagencynames['BVA066'] = 'Harvard Empowerment Solutions (Pty) Ltd';
	oDtiratingagencynames['BVA046'] = 'Honeycomb BEE Ratings (Pty) Ltd';
	oDtiratingagencynames['BVA034'] = 'Inforcomm (Pty) Ltd';
	oDtiratingagencynames['BVA055'] = 'Ingcazi Ratings (Pty) Ltd';
	oDtiratingagencynames['BVA126'] = 'Inkomba Verification Agency CC';
	oDtiratingagencynames['BVA052'] = 'Integra Scores (Pty) Ltd';
	oDtiratingagencynames['BVA134'] = 'Iquad bfn Verification Services (Pty) Ltd';
	oDtiratingagencynames['BVA135'] = 'Iquad el Verification Services (Pty) Ltd';
	oDtiratingagencynames['BVA133'] = 'Iquad gtn Verification Services (Pty) Ltd';
	oDtiratingagencynames['BVA132'] = 'Iquad kzn Verification Services (Pty) Ltd';
	oDtiratingagencynames['BVA131'] = 'Iquad Verification Services ctn (Pty) Ltd';
	oDtiratingagencynames['BVA059'] = 'Izikhulu Consulting cc t/a Izikhulu BEE Ratings';
	oDtiratingagencynames['BVA022'] = 'KBonga BEE Verification Agency';
	oDtiratingagencynames['BVA123'] = 'Largoscape CC t/a Nofesa BEE Verification Agency';
	oDtiratingagencynames['BVA017'] = 'lquad Verification Services (Pty) Ltd';
	oDtiratingagencynames['BVA117'] = 'M-PowerRatings (Pty) Ltd';
	oDtiratingagencynames['BVA090'] = 'Mindwalk Consulting (Pty) Ltd';
	oDtiratingagencynames['BVA025'] = 'Moloto BEE Verifications CC';
	oDtiratingagencynames['BVA080'] = 'Mosela Rating Agency (Pty) Ltd';
	oDtiratingagencynames['BVA020'] = 'National Empowerment Rating Agency (Pty) Ltd';
	oDtiratingagencynames['BVA081'] = 'Phadiso (BEE) Verification (Pty) Ltd';
	oDtiratingagencynames['BVA047'] = 'PKF BEE Solutions (Pty) Ltd';
	oDtiratingagencynames['BVA100'] = 'Premier Verification (Pty) Ltd (The Accreditation Agency)';
	oDtiratingagencynames['BVA069'] = 'Provincial Verification Agency cc t/a BLogic Verification Agency';
	oDtiratingagencynames['BVA139'] = 'Renaissance SA Rating (Pty) Ltd';
	oDtiratingagencynames['BVA109'] = 'SASDA Rating Agency a division of SASDA (Pty) Ltd';
	oDtiratingagencynames['BVA121'] = 'SERA Western Cape (Pty) Ltd';
	oDtiratingagencynames['BVA094'] = 'Simunye Resources CC';
	oDtiratingagencynames['BVA099'] = 'SizweNtsaluba VSP Services';
	oDtiratingagencynames['BVA032'] = 'Small Enterprises Rating Agency (Pty) Ltd';
	oDtiratingagencynames['BVA027'] = 'SME Verification CC';
	oDtiratingagencynames['BVA129'] = 'Symphony Investor Communications (Pty) Ltd';
	oDtiratingagencynames['BVA151'] = 'Transformex CC';
	oDtiratingagencynames['BVA116'] = 'Ukuthenga Verification Solutions CC';
	oDtiratingagencynames['BVA078'] = 'Vericom (Pty) Ltd';
	oDtiratingagencynames['BVA013'] = 'Prostart Traders 24 (Pty) Ltd t/a CENFED BEE Verification Agency';

	var aDtiratingagencynames = new Array();

	$.each(oDtiratingagencynames, function(cBodyno, cName) {
		aDtiratingagencynames.push(cName);
	});



	$("#iDtiratingagencyname").autocomplete(aDtiratingagencynames, {

		width: 398,
		highlight: false,
		mustMatch: true,
		multiple: false,
		matchContains: true,
		scroll: true,
		scrollHeight: 300
	});



	var aProducttags = [

	'Minerals',
	'Ores',
	'Base Metals',
	'Precious Metals',
	'Basic Steels',
	'High Speed Steels',
	'Stainless Steel Alloys',
	'Nickel Based Super Alloys',
	'Titanium Based Super Alloys',
	'Aluminium Based alloys',
	'Cobalt Based Super alloys',
	'Metal Solids',
	'Turnings',
	'Explosives',
	'Pyrotechnics',
	'Propellants',
	'Igniters',
	'Earth Metals',
	'Rare Earth Metals',
	'Transition Metals',
	'Noble gases',
	'Isotapes',
	'Indicators and Reagents',
	'Flame Retardents',
	'Anti Oxidents',
	'Catalysts',
	'Buffers',
	'Colloids',
	'Surfactants',
	'Curing agents',
	'Fluid loss additives',
	'Clay Stabilizers',
	'Emulsion Breakers',
	'Friction Reducers',
	'Paraffin Asphaltene Control Agents',
	'Mud removal mixtures',
	'Anti Sludgers',
	'Anti Gas migration Agents',
	'Expanding agents',
	'Extenders',
	'Oil Well Sealants',
	'Corrosion Inhibitors',
	'Gas Hydrate Controllers',
	'Scale Controllers',
	'Chemical Scavengers',
	'Retarders',
	'Iron Controllers',
	'Hydrocarbonated Solvents',
	'Oxygenated Solvents',
	'Petroleum and Distillates',
	'Solid and Gel Fuels',
	'Fuel Oils',
	'Gaseous Fuels',
	'Fuel additives',
	'Lubricating Additives',
	'Anti corrosives',
	'Greases',
	'Nuclear Fuel',
	'Fission fuel assemblies',
	'Cutting Equipment',
	'Crushers and Breakers and Grinders',
	'Mechanized Ground Support Systems',
	'Secondary Rock Breaking Systems',
	'Rock Drills',
	'Explosive Loading machinery',
	'Undergroung mining service vehicles',
	'Drilling and operation machinery',
	'Drilling and Operation accessories',
	'Acidizing Equipment',
	'Cementation equipment',
	'Fracturing equipment',
	'Sand control equipment',
	'Completion tools and equipment',
	'Conventional Drilling tools',
	'Drilling bits',
	'Directional Drilling Equipment',
	'Perforating Equipment',
	'Coiled Tubing Equipment',
	'Drilling and workover rigs and equipment',
	'Multilateral equipment',
	'Casing Exit tools',
	'Drilling Mud materials',
	'Expandable downhole Equipment',
	'Oil Well Cement',
	'Desanding Equipments',
	'Downhole jetpumps and anchors',
	'Downhole pumps',
	'Export pumps',
	'Gas Treating Equipment',
	'Heavy Equipment Components',
	'Grinding and Sanding and Polishing Equipment and supplies',
	'Separation machinery and equipment',
	'Assembly Machines',
	'Coating systems',
	'Gas Liquid contacting systems',
	'Metal Grinding Machines',
	'Metal Cutting Machines',
	'Metal Cutting Tools',
	'Metal Drilling Machines',
	'Metal Boring Machines',
	'Metal Bending Machines',
	'Engines',
	'Engine components and accessories',
	'Hydraulic Presses',
	'Hydraulic Cylinders and Pistons',
	'Air Fittings and Connectors'
	];

	var aProducttags = aProducttags;
	$("#iProducttags").autocomplete(aProducttags, {
			width: 749,
			highlight: false,
			multiple: true,
			matchContains: true,
			multipleSeparator: ", ",
			scroll: true,
			scrollHeight: 300
		});

	$('a#iRemovelastdirector').click(function(){
		$('table#iDirectorslist tr:last').remove();
	});
*/

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
	oMunicipalities['EC']['EC142'] = "Senqu";
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
