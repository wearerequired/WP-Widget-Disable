module.exports = {
	dist: {
		options: {
			cwd            : '',
			domainPath     : '/languages',
			exclude        : ['release/.*'],
			include        : [],
			mainFile       : 'wp-widget-disable.php',
			potComments    : '',
			potFilename    : 'wp-widget-disable.pot',
			potHeaders     : {
				poedit                 : true,
				'x-poedit-keywordslist': true,
				'report-msgid-bugs-to' : 'http://required.ch',
				'last-translator'      : 'required+',
				'language-team'        : 'required+ <support@required.ch>',
				'x-poedit-country'     : 'Switzerland'
			},
			processPot     : null,
			type           : 'wp-plugin',
			updateTimestamp: false
		}
	}
}
