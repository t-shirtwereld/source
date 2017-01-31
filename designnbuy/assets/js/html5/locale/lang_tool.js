svgEditor.readLang({
	lang: currentStore,
	dir : "ltr",
	elementLabel: $j.parseJSON(langData).elementLabel,
	rollOver: $j.parseJSON(langData).rollOver,
	colorPicker: {
		text: {
			title: "Drag Markers To Pick A Color",
			newColor: "nouveau",//new
			currentColor: "courant",//current
			ok: "D'accord",//OK
			cancel: "annuler"//Cancel
		},
		tooltips: {
			colors: {
				newColor: "Nouvelle couleur - Presse & ldquo; OK & rdquo; pour engager",//New Color - Press &ldquo;OK&rdquo; To Commit
				currentColor: "Cliquez pour revenir à la couleur d'origine"//Click To Revert To Original Color
			},
			buttons: {
				ok: "Engagez-vous à cette sélection Couleur",//Commit To This Color Selection
				cancel: "Annuler et revenir à la couleur d'origine"//Cancel And Revert To Original Color
			},
			hue: {
				radio: "Défini sur & ldquo; Hue & rdquo; mode couleur",//Set To &ldquo;Hue&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Hue & rdquo; Valeur (0-360 & deg;)"//Enter A &ldquo;Hue&rdquo; Value (0-360&deg;)
			},
			saturation: {
				radio: "Défini sur & ldquo; Saturation & rdquo; mode couleur",//Set To &ldquo;Saturation&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Saturation & rdquo; Valeur (0-100%)"//Enter A &ldquo;Saturation&rdquo; Value (0-100%)
			},
			value: {
				radio: "Défini sur & ldquo; Valeur & rdquo; mode couleur",//Set To &ldquo;Value&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Valeur & rdquo; Valeur (0-100%)"//Enter A &ldquo;Value&rdquo; Value (0-100%)
			},
			red: {
				radio: "Défini sur & ldquo; Red & rdquo; mode couleur",//Set To &ldquo;Red&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Red & rdquo; Valeur (0-255)"//Enter A &ldquo;Red&rdquo; Value (0-255)
			},
			green: {
				radio: "Défini sur & ldquo; Green & rdquo; mode couleur",//Set To &ldquo;Green&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Green & rdquo; Valeur (0-255)"//Enter A &ldquo;Green&rdquo; Value (0-255)
			},
			blue: {
				radio: "Défini sur & ldquo; Blue & rdquo; mode couleur",//Set To &ldquo;Blue&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Blue & rdquo; Valeur (0-255)"//Enter A &ldquo;Blue&rdquo; Value (0-255)
			},
			alpha: {
				radio: "Défini sur & ldquo; Alpha & rdquo; mode couleur",//Set To &ldquo;Alpha&rdquo; Color Mode
				textbox: "Entrez A & ldquo; Alpha & rdquo; Valeur (0-100)"//Enter A &ldquo;Alpha&rdquo; Value (0-100)
			},
			hex: {
				textbox: "Entrez A & ldquo; Hex & rdquo; Couleur de la valeur (# 000000- # ffffff)",//Enter A &ldquo;Hex&rdquo; Color Value (#000000-#ffffff)
				alpha: "Entrez A & ldquo; Alpha & rdquo; Valeur (# 00- # FF)"//Enter A &ldquo;Alpha&rdquo; Value (#00-#ff)
			}
		}
	},
	qrCodePanel:$j.parseJSON(langData).qrCodePanel,
	notification: $j.parseJSON(langData).notification
});