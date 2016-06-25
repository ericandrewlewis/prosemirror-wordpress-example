import {ProseMirror} from "prosemirror/dist/edit"
import {schema} from "prosemirror/dist/schema-basic"
import {exampleSetup, buildMenuItems} from "prosemirror/dist/example-setup"
import {tooltipMenu, menuBar} from "prosemirror/dist/menu"

let place = document.querySelector("#prosemirror-editor")
let content = null
if ( document.querySelector("[name=prosemirror-editor-content]").value ) {
  try {
    content = schema.nodeFromJSON( JSON.parse( document.querySelector("[name=prosemirror-editor-content]").value ) )
  }
  catch(e) {}
}

let pm = window.pm = new ProseMirror({
  place: place,
  schema: schema,
  doc: content,
  plugins: [exampleSetup.config({menuBar: false, tooltipMenu: false})]
})

pm.on.transform.add( () => {
  document.querySelector('[name=prosemirror-editor-content]').value = JSON.stringify( pm.doc.toJSON() )
})

let menu = buildMenuItems(schema)

setMenuStyle(place.getAttribute("menustyle") || "bar")

function setMenuStyle(type) {
  if (type == "bar") {
    tooltipMenu.detach(pm)
    menuBar.config({float: true, content: menu.fullMenu}).attach(pm)
  } else {
    menuBar.detach(pm)
    tooltipMenu.config({selectedBlockMenu: true,
                        inlineContent: menu.inlineMenu,
                        blockContent: menu.blockMenu}).attach(pm)
  }
}

let menuStyle = document.querySelector("#menustyle")
if (menuStyle) menuStyle.addEventListener("change", () => setMenuStyle(menuStyle.value))