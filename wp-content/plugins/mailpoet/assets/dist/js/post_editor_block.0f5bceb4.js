!function(modules){var installedModules={};function __webpack_require__(moduleId){if(installedModules[moduleId])return installedModules[moduleId].exports;var module=installedModules[moduleId]={i:moduleId,l:!1,exports:{}};return modules[moduleId].call(module.exports,module,module.exports,__webpack_require__),module.l=!0,module.exports}__webpack_require__.m=modules,__webpack_require__.c=installedModules,__webpack_require__.d=function(exports,name,getter){__webpack_require__.o(exports,name)||Object.defineProperty(exports,name,{enumerable:!0,get:getter})},__webpack_require__.r=function(exports){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(exports,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(exports,"__esModule",{value:!0})},__webpack_require__.t=function(value,mode){if(1&mode&&(value=__webpack_require__(value)),8&mode)return value;if(4&mode&&"object"==typeof value&&value&&value.__esModule)return value;var ns=Object.create(null);if(__webpack_require__.r(ns),Object.defineProperty(ns,"default",{enumerable:!0,value:value}),2&mode&&"string"!=typeof value)for(var key in value)__webpack_require__.d(ns,key,function(key){return value[key]}.bind(null,key));return ns},__webpack_require__.n=function(module){var getter=module&&module.__esModule?function(){return module.default}:function(){return module};return __webpack_require__.d(getter,"a",getter),getter},__webpack_require__.o=function(object,property){return Object.prototype.hasOwnProperty.call(object,property)},__webpack_require__.p="",__webpack_require__(__webpack_require__.s=3)}([function(module,exports,__webpack_require__){module.exports=__webpack_require__(1)()},function(module,exports,__webpack_require__){"use strict";var ReactPropTypesSecret=__webpack_require__(2);function emptyFunction(){}function emptyFunctionWithReset(){}emptyFunctionWithReset.resetWarningCache=emptyFunction,module.exports=function(){function shim(props,propName,componentName,location,propFullName,secret){if(secret!==ReactPropTypesSecret){var err=new Error("Calling PropTypes validators directly is not supported by the `prop-types` package. Use PropTypes.checkPropTypes() to call them. Read more at http://fb.me/use-check-prop-types");throw err.name="Invariant Violation",err}}function getShim(){return shim}shim.isRequired=shim;var ReactPropTypes={array:shim,bool:shim,func:shim,number:shim,object:shim,string:shim,symbol:shim,any:shim,arrayOf:getShim,element:shim,elementType:shim,instanceOf:getShim,node:shim,objectOf:getShim,oneOf:getShim,oneOfType:getShim,shape:getShim,exact:getShim,checkPropTypes:emptyFunctionWithReset,resetWarningCache:emptyFunction};return ReactPropTypes.PropTypes=ReactPropTypes,ReactPropTypes}},function(module,exports,__webpack_require__){"use strict";module.exports="SECRET_DO_NOT_PASS_THIS_OR_YOU_WILL_BE_FIRED"},function(module,__webpack_exports__,__webpack_require__){"use strict";__webpack_require__.r(__webpack_exports__);const wp=window.wp,{G:G,Path:Path,SVG:SVG}=wp.components;var icon=React.createElement(SVG,{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 152.02 156.4"},React.createElement(G,null,React.createElement(Path,{d:"M37.71,89.1c3.5,0,5.9-.8,7.2-2.3a8,8,0,0,0,2-5.4V35.7l17,45.1a12.68,12.68,0,0,0,3.7,5.4c1.6,1.3,4,2,7.2,2a12.54,12.54,0,0,0,5.9-1.4,8.41,8.41,0,0,0,3.9-5l18.1-50V81a8.53,8.53,0,0,0,2.1,6.1c1.4,1.4,3.7,2.2,6.9,2.2,3.5,0,5.9-.8,7.2-2.3a8,8,0,0,0,2-5.4V8.7a7.48,7.48,0,0,0-3.3-6.6c-2.1-1.4-5-2.1-8.6-2.1a19.3,19.3,0,0,0-9.4,2,11.63,11.63,0,0,0-5.1,6.8L74.91,67.1,54.41,8.4a12.4,12.4,0,0,0-4.5-6.2c-2.1-1.5-5-2.2-8.8-2.2a16.51,16.51,0,0,0-8.9,2.1c-2.3,1.5-3.5,3.9-3.5,7.2V80.8c0,2.8.7,4.8,2,6.2C32.21,88.4,34.41,89.1,37.71,89.1Z"}),React.createElement(Path,{d:"M149,116.6l-2.4-1.9a7.4,7.4,0,0,0-9.4.3,19.65,19.65,0,0,1-12.5,4.6h-21.4A37.08,37.08,0,0,0,77,130.5l-1.1,1.2-1.1-1.1a37.25,37.25,0,0,0-26.3-10.9H27a19.59,19.59,0,0,1-12.4-4.6,7.28,7.28,0,0,0-9.4-.3l-2.4,1.9A7.43,7.43,0,0,0,0,122.2a7.14,7.14,0,0,0,2.4,5.7A37.28,37.28,0,0,0,27,137.4h21.6a19.59,19.59,0,0,1,18.9,14.4v.2c.1.7,1.2,4.4,8.5,4.4s8.4-3.7,8.5-4.4v-.2a19.59,19.59,0,0,1,18.9-14.4H125a37.28,37.28,0,0,0,24.6-9.5,7.42,7.42,0,0,0,2.4-5.7A7.86,7.86,0,0,0,149,116.6Z"}))),prop_types=__webpack_require__(0),prop_types_default=__webpack_require__.n(prop_types);const edit_wp=window.wp,{Placeholder:Placeholder,PanelBody:PanelBody}=edit_wp.components,{BlockIcon:BlockIcon,InspectorControls:InspectorControls}=edit_wp.blockEditor,ServerSideRender=edit_wp.serverSideRender,allForms=window.mailpoet_forms;function Edit({attributes:attributes,setAttributes:setAttributes}){function selectFormSettings(){return React.createElement("div",{className:"mailpoet-block-create-new-content"},React.createElement("a",{href:"admin.php?page=mailpoet-form-editor&action=create",target:"_blank",className:"mailpoet-block-create-new-link"},window.locale.createForm),Array.isArray(allForms)?0===allForms.length?null:React.createElement("select",{onChange:event=>{setAttributes({formId:parseInt(event.target.value,10)})},className:"mailpoet-block-create-forms-list",value:attributes.formId},React.createElement("option",{value:"",disabled:!0,selected:!0},window.locale.selectForm),allForms.map((form=>React.createElement("option",{value:form.id},form.name)))):null)}return React.createElement(React.Fragment,null,React.createElement(InspectorControls,null,React.createElement(PanelBody,{title:"MailPoet Subscription Form",initialOpen:!0},selectFormSettings())),React.createElement("div",{className:"mailpoet-block-div"},null===attributes.formId&&React.createElement(Placeholder,{className:"mailpoet-block-create-new",icon:React.createElement(BlockIcon,{icon:icon,showColors:!0}),label:window.locale.subscriptionForm},selectFormSettings()),null!==attributes.formId&&React.createElement(ServerSideRender,{block:"mailpoet/subscription-form-block-render",attributes:{formId:attributes.formId}})))}Edit.propTypes={attributes:prop_types_default.a.shape({formId:prop_types_default.a.number}).isRequired,setAttributes:prop_types_default.a.func.isRequired};var edit=Edit;const form_block_wp=window.wp,{registerBlockType:registerBlockType}=form_block_wp.blocks;registerBlockType("mailpoet/subscription-form-block-render",{title:window.locale.subscriptionForm,attributes:{formId:{type:"number",default:null}},supports:{inserter:!1}}),registerBlockType("mailpoet/subscription-form-block",{title:window.locale.subscriptionForm,icon:icon,category:"widgets",example:{},attributes:{formId:{type:"number",default:null}},edit:edit,save:()=>null})}]);