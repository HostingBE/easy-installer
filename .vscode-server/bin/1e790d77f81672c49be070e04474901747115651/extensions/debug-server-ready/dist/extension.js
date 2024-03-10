(()=>{"use strict";var e={496:e=>{e.exports=require("vscode")},113:e=>{e.exports=require("crypto")},837:e=>{e.exports=require("util")}},t={};function s(i){var r=t[i];if(void 0!==r)return r.exports;var o=t[i]={exports:{}};return e[i](o,o.exports,s),o.exports}var i={};(()=>{var e=i;Object.defineProperty(e,"__esModule",{value:!0}),e.activate=void 0;const t=s(496),r=s(837),o=s(113),n=/(:?\x1b\[|\x9B)[=?>!]?[\d;:]*["$#'* ]?[a-zA-Z@^`{}|~]/g;class a{constructor(){this._fired=!1}get hasFired(){return this._fired}fire(){this._fired=!0}}class d extends t.Disposable{static start(e){if(e.configuration.serverReadyAction){let t=d.detectors.get(e);return t||(t=new d(e),d.detectors.set(e,t)),t}}static stop(e){const t=d.detectors.get(e);t&&(d.detectors.delete(e),t.sessionStopped(),t.dispose())}static rememberShellPid(e,t){const s=d.detectors.get(e);s&&(s.shellPid=t)}static async startListeningTerminalData(){this.terminalDataListener||(this.terminalDataListener=t.window.onDidWriteTerminalData((async e=>{const t=await e.terminal.processId,s=function(e){return e&&(e=e.replace(n,"")),e}(e.data);for(const[,e]of this.detectors)if(e.shellPid===t)return void e.detectPattern(s);for(const[,e]of this.detectors)if(e.detectPattern(s))return})))}constructor(e){super((()=>this.internalDispose())),this.session=e,this.stoppedEmitter=new t.EventEmitter,this.onDidSessionStop=this.stoppedEmitter.event,this.disposables=new Set([]),e.parentSession?this.trigger=d.start(e.parentSession)?.trigger??new a:this.trigger=new a,this.regexp=new RegExp(e.configuration.serverReadyAction.pattern||"listening on.* (https?://\\S+|[0-9]+)","i")}internalDispose(){this.disposables.forEach((e=>e.dispose())),this.disposables.clear()}sessionStopped(){this.stoppedEmitter.fire()}detectPattern(e){if(!this.trigger.hasFired){const t=this.regexp.exec(e);if(t&&t.length>=1)return this.openExternalWithString(this.session,t.length>1?t[1]:""),this.trigger.fire(),!0}return!1}openExternalWithString(e,s){const i=e.configuration.serverReadyAction;let o;if(""===s){const e=i.uriFormat||"";if(e.indexOf("%s")>=0){const s=t.l10n.t("Format uri ('{0}') uses a substitution placeholder but pattern did not capture anything.",e);return void t.window.showErrorMessage(s,{modal:!0}).then((e=>{}))}o=e}else{const e=i.uriFormat||(/^[0-9]+$/.test(s)?"http://localhost:%s":"%s");if(2!==e.split("%s").length){const s=t.l10n.t("Format uri ('{0}') must contain exactly one substitution placeholder.",e);return void t.window.showErrorMessage(s,{modal:!0}).then((e=>{}))}o=r.format(e,s)}this.openExternalWithUri(e,o)}async openExternalWithUri(e,s){const i=e.configuration.serverReadyAction;switch(i.action||"openExternally"){case"openExternally":await t.env.openExternal(t.Uri.parse(s));break;case"debugWithChrome":await this.debugWithBrowser("pwa-chrome",e,s);break;case"debugWithEdge":await this.debugWithBrowser("pwa-msedge",e,s);break;case"startDebugging":i.config?await this.startDebugSession(e,i.config.name,i.config):await this.startDebugSession(e,i.name||"unspecified")}}async debugWithBrowser(e,s,i){if(!s.configuration.serverReadyAction.killOnServerStop)return void await this.startBrowserDebugSession(e,s,i);const r=(0,o.randomUUID)(),n=new t.CancellationTokenSource,a=this.catchStartedDebugSession((e=>e.configuration._debugServerReadySessionId===r),n.token);if(!await this.startBrowserDebugSession(e,s,i,r))return n.cancel(),void n.dispose();const d=await a;if(n.dispose(),!d)return;const c=this.onDidSessionStop((async()=>{c.dispose(),this.disposables.delete(c),await t.debug.stopDebugging(d)}));this.disposables.add(c)}startBrowserDebugSession(e,s,i,r){return t.debug.startDebugging(s.workspaceFolder,{type:e,name:"Browser Debug",request:"launch",url:i,webRoot:s.configuration.serverReadyAction.webRoot||"${workspaceFolder}",_debugServerReadySessionId:r})}async startDebugSession(e,s,i){if(!e.configuration.serverReadyAction.killOnServerStop)return void await t.debug.startDebugging(e.workspaceFolder,i??s);const r=new t.CancellationTokenSource,o=this.catchStartedDebugSession((e=>e.name===s),r.token);if(!await t.debug.startDebugging(e.workspaceFolder,i??s))return r.cancel(),void r.dispose();const n=await o;if(r.dispose(),!n)return;const a=this.onDidSessionStop((async()=>{a.dispose(),this.disposables.delete(a),await t.debug.stopDebugging(n)}));this.disposables.add(a)}catchStartedDebugSession(e,s){return new Promise((i=>{const r=e=>{n.dispose(),o.dispose(),this.disposables.delete(n),this.disposables.delete(o),i(e)},o=s.onCancellationRequested(r),n=t.debug.onDidStartDebugSession((t=>{e(t)&&r(t)}));this.disposables.add(n),this.disposables.add(o)}))}}d.detectors=new Map,e.activate=function(e){e.subscriptions.push(t.debug.onDidStartDebugSession((e=>{e.configuration.serverReadyAction&&d.start(e)&&d.startListeningTerminalData()}))),e.subscriptions.push(t.debug.onDidTerminateDebugSession((e=>{d.stop(e)})));const s=new Set;e.subscriptions.push(t.debug.registerDebugConfigurationProvider("*",{resolveDebugConfigurationWithSubstitutedVariables:(i,r)=>(r.type&&r.serverReadyAction&&(s.has(r.type)||(s.add(r.type),function(e,s){e.subscriptions.push(t.debug.registerDebugAdapterTrackerFactory(s,{createDebugAdapterTracker(e){const t=d.start(e);if(t){let s;return{onDidSendMessage:e=>{if("event"===e.type&&"output"===e.event&&e.body)switch(e.body.category){case"console":case"stderr":case"stdout":e.body.output&&t.detectPattern(e.body.output)}"request"===e.type&&"runInTerminal"===e.command&&e.arguments&&"integrated"===e.arguments.kind&&(s=e.seq)},onWillReceiveMessage:t=>{s&&"response"===t.type&&"runInTerminal"===t.command&&t.body&&s===t.request_seq&&(s=void 0,d.rememberShellPid(e,t.body.shellProcessId))}}}}}))}(e,r.type))),r)}))}})();var r=exports;for(var o in i)r[o]=i[o];i.__esModule&&Object.defineProperty(r,"__esModule",{value:!0})})();
//# sourceMappingURL=https://ticino.blob.core.windows.net/sourcemaps/1e790d77f81672c49be070e04474901747115651/extensions/debug-server-ready/dist/extension.js.map