!function(e){function t(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}var n={};t.m=e,t.c=n,t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{configurable:!1,enumerable:!0,get:o})},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},t.p="",t(t.s=2)}([function(e,t,n){"use strict";var o={};o.ccbpress=wp.element.createElement("svg",{xmlns:"http://www.w3.org/2000/svg",width:"20",height:"20",viewBox:"0 0 307.82812 275.74194",id:"svg2"},wp.element.createElement("g",{id:"layer1",transform:"translate(-92.192 -2010.14)"},wp.element.createElement("path",{d:"m 246.1061,2020.1391 c -34.10897,0 -68.21804,1.0796 -71.95703,3.2383 -7.47798,4.3174 -71.95703,115.998 -71.95703,124.6328 0,8.6348 64.47905,120.3154 71.95703,124.6328 7.47799,4.3174 136.43608,4.3174 143.91406,0 7.47799,-4.3174 71.95703,-115.998 71.95703,-124.6328 0,-8.6348 -64.47904,-120.3154 -71.95703,-124.6328 -3.73899,-2.1587 -37.84805,-3.2383 -71.95703,-3.2383 z m -73.81836,49.6406 48.94727,0 57.70703,78.2305 -57.70703,78.2324 -48.94727,0 57.70508,-78.2324 -57.70508,-78.2305 z m 60.98242,0 48.94922,0 57.70508,78.2305 -57.70898,78.2324 -48.94532,0 57.70313,-78.2324 -57.70313,-78.2305 z",id:"path4734",fill:"#f55"}))),t.a=o},function(e,t,n){"use strict";function o(){return fetch(ccbpress_core_blocks.api_url+"ccbpress/v1/admin/groups",{method:"POST",headers:{Accept:"application/json","Content-Type":"application/json"},body:JSON.stringify({_wpnonce:ccbpress_core_blocks.api_nonce})})}function r(e){if(null!==e)return fetch(ccbpress_core_blocks.api_url+"ccbpress/v1/admin/group/"+e,{method:"POST",headers:{Accept:"application/json","Content-Type":"application/json"},body:JSON.stringify({_wpnonce:ccbpress_core_blocks.api_nonce})})}function a(e){return null!==e&&fetch(ccbpress_core_blocks.api_url+"ccbpress/v1/admin/is-form-active/"+e,{method:"POST",headers:{Accept:"application/json","Content-Type":"application/json"},body:JSON.stringify({_wpnonce:ccbpress_core_blocks.api_nonce})})}t.b=o,t.a=r,t.c=a},function(e,t,n){"use strict";Object.defineProperty(t,"__esModule",{value:!0});n(3),n(6),n(9)},function(e,t,n){"use strict";function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!==typeof t&&"function"!==typeof t?e:t}function a(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var l=n(4),i=(n.n(l),n(5)),s=(n.n(i),n(0)),c=function(){function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),u=wp.element,p=u.Component,m=u.Fragment,f=wp.i18n.__,b=wp.blocks.registerBlockType,d=wp.editor,g=d.InspectorControls,h=d.PanelColorSettings,w=d.ContrastChecker,y=wp.components,v=y.PanelBody,_=y.ToggleControl,C=y.TextControl,E=y.Disabled,k=function(e){function t(){o(this,t);var e=r(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return e._setButtonBackgroundColor=function(t){e.props.setAttributes({buttonBackgroundColor:t})},e._setButtonTextColor=function(t){e.props.setAttributes({buttonTextColor:t})},e}return a(t,e),c(t,[{key:"render",value:function(){var e=this.props,t=e.attributes,n=e.setAttributes,o=e.className,r=t.showForgotPassword,a=t.buttonBackgroundColor,l=t.buttonTextColor,i=t.buttonText,s=wp.element.createElement(g,{key:"inspector"},wp.element.createElement(v,{title:f("Form Settings")},wp.element.createElement(_,{label:f("Show Forgot Password"),checked:r,onChange:function(){return n({showForgotPassword:!r})}}),wp.element.createElement(C,{label:f("Submit Button Text"),value:i,onChange:function(e){return n({buttonText:e})}})),wp.element.createElement(h,{title:f("Submit Button Colors"),initialOpen:!1,colorSettings:[{value:a,onChange:this._setButtonBackgroundColor,label:f("Background Color")},{value:l,onChange:this._setButtonTextColor,label:f("Text Color")}]},wp.element.createElement(w,{textColor:l,backgroundColor:a})));return wp.element.createElement(m,null,s,wp.element.createElement(E,null,wp.element.createElement("div",{className:o,title:"title"},wp.element.createElement("form",{class:"ccbpress-core-login",method:"post",target:"_blank"},wp.element.createElement("label",null,f("Username:")),wp.element.createElement("input",{type:"text",value:""}),wp.element.createElement("label",null,f("Password:")),wp.element.createElement("input",{type:"password",value:""}),wp.element.createElement("input",{type:"submit",value:i,style:{backgroundColor:a||"",color:l||""}})),r&&wp.element.createElement("p",null,wp.element.createElement("a",{href:"#"},f("Forgot username or password?"))))))}}]),t}(p);b("ccbpress/login",{title:f("CCB Login"),icon:s.a.ccbpress,category:"ccbpress",keywords:[f("church community builder"),f("ccb"),f("ccbpress")],supports:{html:!1},attributes:{showForgotPassword:{type:"boolean",default:!0},buttonBackgroundColor:{type:"string"},buttonTextColor:{type:"string"},buttonText:{type:"string",default:f("Login")}},edit:k,save:function(){return null}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!==typeof t&&"function"!==typeof t?e:t}function a(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var l=n(7),i=(n.n(l),n(8)),s=(n.n(i),n(0)),c=function(){function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),u=wp.element,p=u.Component,m=u.Fragment,f=wp.i18n.__,b=wp.blocks.registerBlockType,d=wp.editor,g=d.InspectorControls,h=d.PanelColorSettings,w=d.ContrastChecker,y=wp.components,v=y.PanelBody,_=y.TextControl,C=y.Disabled,E=function(e){function t(){o(this,t);var e=r(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return e._setBackgroundColor=function(t){e.props.setAttributes({backgroundColor:t})},e._setTextColor=function(t){e.props.setAttributes({textColor:t})},e}return a(t,e),c(t,[{key:"render",value:function(){var e=this.props,t=e.attributes,n=e.setAttributes,o=e.className,r=t.buttonText,a=t.backgroundColor,l=t.textColor,i=wp.element.createElement(g,{key:"inspector"},wp.element.createElement(v,{title:f("Button Settings")},wp.element.createElement(_,{label:f("Button Text"),value:r,onChange:function(e){return n({buttonText:e})}})),wp.element.createElement(h,{title:f("Button Colors"),initialOpen:!1,colorSettings:[{value:a,onChange:this._setBackgroundColor,label:f("Background Color")},{value:l,onChange:this._setTextColor,label:f("Text Color")}]},wp.element.createElement(w,{textColor:l,backgroundColor:a})));return wp.element.createElement(m,null,i,wp.element.createElement(C,null,wp.element.createElement("div",{className:o},wp.element.createElement("form",{className:"ccbpress-core-online-giving",target:"_blank"},wp.element.createElement("input",{type:"submit",value:r,style:{backgroundColor:a||"",color:l||""}})))))}}]),t}(p);b("ccbpress/online-giving",{title:f("Online Giving"),icon:s.a.ccbpress,category:"ccbpress",keywords:[f("church community builder"),f("ccb"),f("ccbpress")],supports:{html:!1},attributes:{buttonText:{type:"string",default:f("Give Now")},backgroundColor:{type:"string"},textColor:{type:"string"}},edit:E,save:function(){return null}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!==typeof t&&"function"!==typeof t?e:t}function a(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var l=n(10),i=(n.n(l),n(11)),s=(n.n(i),n(0)),c=n(12),u=n(1),p=function(){function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),m=wp.element,f=m.Component,b=m.Fragment,d=wp.i18n.__,g=wp.blocks.registerBlockType,h=wp.editor,w=h.InspectorControls,y=h.PanelColorSettings,v=wp.components,_=v.PanelBody,C=v.ToggleControl,E=v.Spinner,k=v.Placeholder,O=v.Disabled,x=function(e){function t(){o(this,t);var e=r(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return e._setBoxBackgroundColor=function(t){e.props.setAttributes({boxBackgroundColor:t})},e._setBoxBorderColor=function(t){e.props.setAttributes({boxBorderColor:t})},e.state={group:null,prevGroupId:null,isLoading:!0,externalData:null},e}return a(t,e),p(t,[{key:"componentDidMount",value:function(){this._getGroupInfo()}},{key:"componentDidUpdate",value:function(e,t){var n=this.props.attributes.groupId;null===this.state.group&&null!==n&&this._getGroupInfo()}},{key:"_getGroupInfo",value:function(){var e=this,t=this.props.attributes.groupId;if(null===t)return void this.setState({isLoading:!1});Object(u.a)(t).then(function(e){return e.json()}).then(function(t){e.setState({group:t,isLoading:!1})})}},{key:"_renderPhoneNumbers",value:function(e){var t=[];if(this._isEmptyObject(e))return t;for(var n in e)("string"===typeof e[n]||e[n]instanceof String)&&t.push(wp.element.createElement("div",{class:"ccbpress-group-info-leader-phone"},e[n]));return t}},{key:"_isEmptyObject",value:function(e){return 0===Object.keys(e).length&&e.constructor===Object}},{key:"_renderRegistrationForms",value:function(e){var t=[];if(this._isEmptyObject(e))return t;for(var n in e)e.hasOwnProperty(n)&&Object(u.c)(e[n]["@attributes"].id)&&t.push(wp.element.createElement("div",{class:"ccbpress-group-info-registration-form"},wp.element.createElement("a",{href:e[n].url.toString()},e[n].name.toString())));return t}},{key:"render",value:function(){var e=this.props,t=e.attributes,n=e.setAttributes,o=e.className,r=t.groupId,a=t.showGroupImage,l=t.showGroupName,i=t.showGroupDesc,u=t.showMainLeader,p=t.showMainLeaderEmail,m=t.showMainLeaderPhone,f=t.showRegistrationForms,g=t.boxBackgroundColor,h=t.boxBorderColor,v=wp.element.createElement(w,{key:"inspector"},wp.element.createElement(_,{title:d("Group Settings")},wp.element.createElement(c.a,{value:r,onChange:function(e){return n({groupId:e})}})),wp.element.createElement(_,{title:d("Display Settings")},wp.element.createElement(C,{label:d("Image"),checked:a,onChange:function(){return n({showGroupImage:!a})}}),wp.element.createElement(C,{label:d("Group Name"),checked:l,onChange:function(e){return n({showGroupName:e})}}),wp.element.createElement(C,{label:d("Description"),checked:i,onChange:function(e){return n({showGroupDesc:e})}}),wp.element.createElement(C,{label:d("Main Leader"),checked:u,onChange:function(e){return n({showMainLeader:e})}}),u&&wp.element.createElement(C,{label:d("Email Address"),checked:p,onChange:function(e){return n({showMainLeaderEmail:e})}}),u&&wp.element.createElement(C,{label:d("Phone Numbers"),checked:m,onChange:function(e){return n({showMainLeaderPhone:e})}}),wp.element.createElement(C,{label:d("Registration Forms"),checked:f,onChange:function(e){return n({showRegistrationForms:e})}})),wp.element.createElement(y,{title:d("Meta Box Colors"),initialOpen:!1,colorSettings:[{value:g,onChange:this._setBoxBackgroundColor,label:d("Background Color")},{value:h,onChange:this._setBoxBorderColor,label:d("Border Color")}]}));return this.state.isLoading?wp.element.createElement(b,null,v,wp.element.createElement(k,{icon:s.a.ccbpress,label:d("Group Information")},wp.element.createElement(E,null))):wp.element.createElement(b,null,v,wp.element.createElement("div",{className:o},(null===r||""===r)&&wp.element.createElement(k,{icon:s.a.ccbpress,label:d("Group Information")},wp.element.createElement(c.a,{value:r,onChange:function(e){return n({groupId:e})}})),wp.element.createElement(O,null,null!==r&&""!==r&&wp.element.createElement("div",null,a&&""!==this.state.group.image&&wp.element.createElement("div",null,wp.element.createElement("img",{src:this.state.group.image})),l&&!this._isEmptyObject(this.state.group.data.name)&&wp.element.createElement("div",{className:"ccbpress-group-info-name"},this.state.group.data.name),i&&!this._isEmptyObject(this.state.group.data.description)&&wp.element.createElement("div",{className:"ccbpress-group-info-desc"},this.state.group.data.description),wp.element.createElement("div",{className:"ccbpress-group-info-details",style:{backgroundColor:g||"",borderColor:h||""}},u&&wp.element.createElement("div",null,wp.element.createElement("div",{className:"ccbpress-group-info-leader-title"},d("Group Leader")),wp.element.createElement("div",{className:"ccbpress-group-info-leader-container"},this.state.group.data.main_leader.image&&wp.element.createElement("img",{className:"ccbpress-group-info-leader-image",src:this.state.group.data.main_leader.image}),wp.element.createElement("div",{className:"ccbpress-group-info-leader-name"},p&&wp.element.createElement("a",{href:"mailto:"+this.state.group.data.main_leader.email.toString()},this.state.group.data.main_leader.full_name.toString()),!p&&wp.element.createElement(b,null,this.state.group.data.main_leader.full_name.toString()))),m&&this._renderPhoneNumbers(this.state.group.data.main_leader.phones)),f&&!this._isEmptyObject(this.state.group.data.registration_forms)&&wp.element.createElement("div",null,wp.element.createElement("div",{className:"ccbpress-group-info-registration-forms-title"},d("Registration Forms")),this._renderRegistrationForms(this.state.group.data.registration_forms)))))))}}],[{key:"getDerivedStateFromProps",value:function(e,t){return e.attributes.groupId!==t.prevGroupId?{group:null,prevGroupId:e.attributes.groupId,isLoading:!0}:null}}]),t}(f);g("ccbpress/group-info",{title:d("Group Information"),description:d("Display group information from Church Community Builder."),icon:s.a.ccbpress,category:"ccbpress",keywords:[d("church community builder"),d("ccb"),d("ccbpress")],supports:{html:!1},attributes:{groupId:{type:"select",default:null},showGroupImage:{type:"boolean",default:!0},showGroupName:{type:"boolean",default:!0},showGroupDesc:{type:"boolean",default:!0},showMainLeader:{type:"boolean",default:!0},showMainLeaderEmail:{type:"boolean",default:!0},showMainLeaderPhone:{type:"boolean",default:!0},showRegistrationForms:{type:"boolean",default:!0},boxBackgroundColor:{type:"string"},boxBorderColor:{type:"string"}},edit:x,save:function(){}})},function(e,t){},function(e,t){},function(e,t,n){"use strict";function o(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!==typeof t&&"function"!==typeof t?e:t}function a(e,t){if("function"!==typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var l=n(1),i=function(){function e(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}return function(t,n,o){return n&&e(t.prototype,n),o&&e(t,o),t}}(),s=wp.i18n.__,c=wp.element.Component,u=wp.components.SelectControl,p=function(e){function t(e){o(this,t);var n=r(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments));return n.state={options:[{id:"",name:s("Loading groups..")}]},Object(l.b)().then(function(e){return e.json()}).then(function(e){return e.unshift({id:"",name:""}),n.setState({options:e})}),n}return a(t,e),i(t,[{key:"render",value:function(){return wp.element.createElement(u,{type:"number",label:s("Please select a group:"),value:this.props.value,onChange:this.props.onChange,options:this.state.options.map(function(e){return{value:e.id,label:e.name}})})}}]),t}(c);t.a=p}]);