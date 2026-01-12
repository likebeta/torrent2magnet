class Torrent2Magnet {
	static convert(file, callback) {
		// Validate file extension
		const fileName = file.name;
		const fileParts = fileName.split('.');
		const extension = fileParts[fileParts.length - 1].toLowerCase();

		if (extension !== 'torrent') {
			callback({ result: 0, url: null });
			return;
		}

		// Read file using FileReader
		const reader = new FileReader();

		reader.onload = function(e) {
			const data = e.target.result;

			// Parse torrent file using Bencode
			Bencode.bdecode_getinfo(data).then(function(info) {
				if (info.info_hash) {
					callback({
						result: 1,
						url: 'magnet:?xt=urn:btih:' + info.info_hash
					});
				} else {
					callback({ result: 0, url: null });
				}
			}).catch(function(err) {
				console.error('Error parsing torrent:', err);
				callback({ result: 0, url: null });
			});
		};

		reader.onerror = function() {
			console.error('Error reading file');
			callback({ result: 0, url: null });
		};

		// Read file as binary string
		reader.readAsBinaryString(file);
	}
}

class Magnet2Torrent {
	static #convertFalse() {
		return { result: 0, url: null };
	}

	static #toTorrent(hashEncode) {
		hashEncode = hashEncode.toUpperCase();
		return 'https://itorrents.org/torrent/' + hashEncode + '.torrent';
	}

	static convert(magnetUrl) {
		if (!magnetUrl) {
			return this.#convertFalse();
		}

		const magnetHead = magnetUrl.substr(0, 8);
		if (magnetHead !== 'magnet:?') {
			return this.#convertFalse();
		}

		const posOfXt = magnetUrl.indexOf('xt');
		if (posOfXt === -1) {
			return this.#convertFalse();
		}

		const posOfAnd = magnetUrl.indexOf('&', posOfXt);
		let posOfMaoHao, hashEncode, hashLen;

		if (posOfAnd === -1) {
			// No '&' after 'xt' - search entire string for last ':'
			posOfMaoHao = magnetUrl.lastIndexOf(':');
			if (posOfMaoHao === -1) {
				return this.#convertFalse();
			}
			hashEncode = magnetUrl.substr(posOfMaoHao + 1);
			if (hashEncode.length !== 40) {
				return this.#convertFalse();
			}
			const url = this.#toTorrent(hashEncode);
			return { result: 1, url: url };
		} else {
			// Has '&' after 'xt' - search for ':' from posOfXt up to posOfAnd
			posOfMaoHao = magnetUrl.indexOf(':', posOfXt);
			// Keep searching to find the LAST ':' before posOfAnd
			let nextColon;
			while ((nextColon = magnetUrl.indexOf(':', posOfMaoHao + 1)) !== -1 && nextColon < posOfAnd) {
				posOfMaoHao = nextColon;
			}
			if (posOfMaoHao === -1 || posOfMaoHao >= posOfAnd) {
				return this.#convertFalse();
			}
			hashLen = posOfAnd - posOfMaoHao - 1;
			hashEncode = magnetUrl.substr(posOfMaoHao + 1, hashLen);
			if (hashEncode.length !== 40) {
				return this.#convertFalse();
			}
			const url = this.#toTorrent(hashEncode);
			return { result: 1, url: url };
		}
	}
}

// Global function for easy access
function magnet2torrent(magnetUrl) {
	return Magnet2Torrent.convert(magnetUrl);
}
