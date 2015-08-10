function showDebugInfo(aDebug) {
    if (console && console.debug && aDebug) {
        console.groupCollapsed(aDebug.shift());
        while(aDebug.length) {
            try {
                var msg = aDebug.shift();
                if (console[msg[0]]) {
                    console[msg[0]](msg[1]);
                } else {
                    console.debug(msg);
                }
            } catch (e) {
                console.log(msg);
                console.log(e);
            }
        }
        console.groupEnd();
    }
}