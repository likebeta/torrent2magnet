// bencode.js - Bencode encoding/decoding library

class Bencode {
	static #bdecode(s, pos) {
		if (typeof pos === 'undefined') {
			pos = 0;
		}
		if (pos >= s.length) {
			return null;
		}

		switch (s.charAt(pos)) {
			case 'd':
				pos++;
				const retval = {};
				retval.isDct = true;
				while (s.charAt(pos) !== 'e') {
					const key = this.#bdecode(s, pos);
					pos = key.pos;
					const val = this.#bdecode(s, pos);
					pos = val.pos;
					if (key.value === null || val.value === null) {
						break;
					}
					retval[key.value] = val.value;
				}
				pos++;
				return { value: retval, pos: pos };

			case 'l':
				pos++;
				const retvalList = [];
				while (s.charAt(pos) !== 'e') {
					const val = this.#bdecode(s, pos);
					pos = val.pos;
					if (val.value === null) {
						break;
					}
					retvalList.push(val.value);
				}
				pos++;
				return { value: retvalList, pos: pos };

			case 'i':
				pos++;
				const digits = s.indexOf('e', pos) - pos;
				const val = parseInt(s.substr(pos, digits), 10);
				pos += digits + 1;
				return { value: val, pos: pos };

			default:
				const strDigits = s.indexOf(':', pos) - pos;
				if (strDigits < 0 || strDigits > 20) {
					return { value: null, pos: pos };
				}
				const len = parseInt(s.substr(pos, strDigits), 10);
				pos += strDigits + 1;
				const str = s.substr(pos, len);
				pos += len;
				return { value: str, pos: pos };
		}
		return { value: null, pos: pos };
	}

	static #bencode(d) {
		// Check if it's a dictionary (has isDct property) - must check before Array.isArray
		if (d && d.isDct === true) {
			let ret = 'd';
			// Sort keys
			const keys = Object.keys(d).filter(k => k !== 'isDct').sort();
			for (let i = 0; i < keys.length; i++) {
				const key = keys[i];
				const value = d[key];
				ret += key.length + ':' + key;
				if (typeof value === 'string') {
					ret += value.length + ':' + value;
				} else if (typeof value === 'number') {
					ret += 'i' + value + 'e';
				} else {
					ret += this.#bencode(value);
				}
			}
			return ret + 'e';
		} else if (Array.isArray(d)) {
			// It's a list
			let ret = 'l';
			for (let i = 0; i < d.length; i++) {
				const value = d[i];
				if (typeof value === 'string') {
					ret += value.length + ':' + value;
				} else if (typeof value === 'number') {
					ret += 'i' + value + 'e';
				} else {
					ret += this.#bencode(value);
				}
			}
			return ret + 'e';
		} else if (typeof d === 'string') {
			return d.length + ':' + d;
		} else if (typeof d === 'number') {
			return 'i' + d + 'e';
		}
		return null;
	}

	static #sha1(data) {
		// Convert binary string to Uint8Array
		const bytes = new Uint8Array(data.length);
		for (let i = 0; i < data.length; i++) {
			bytes[i] = data.charCodeAt(i);
		}

		// Use SubtleCrypto for SHA1
		return crypto.subtle.digest('SHA-1', bytes).then(hashBuffer => {
			// Convert buffer to hex string
			const hashArray = Array.from(new Uint8Array(hashBuffer));
			return hashArray.map(b => b.toString(16).padStart(2, '0')).join('').toUpperCase();
		});
	}

	// Public API
	static bdecode(s) {
		return this.#bdecode(s, 0).value;
	}

	static bencode(d) {
		return this.#bencode(d);
	}

	static bdecode_getinfo(data) {
		return new Promise((resolve, reject) => {
			const decoded = this.#bdecode(data);
			const t = decoded.value;

			// Calculate info_hash
			const infoBencoded = this.#bencode(t.info);
			this.#sha1(infoBencoded).then(hash => {
				t.info_hash = hash;

				// Process file info
				if (Array.isArray(t.info.files)) {
					// Multifile torrent
					t.info.size = 0;
					t.info.filecount = 0;

					for (let i = 0; i < t.info.files.length; i++) {
						t.info.filecount++;
						t.info.size += t.info.files[i].length;
					}
				} else {
					// Single file torrent
					t.info.size = t.info.length;
					t.info.filecount = 1;
					t.info.files = [{
						path: t.info.name,
						length: t.info.length
					}];
				}

				resolve(t);
			}).catch(reject);
		});
	}
}
