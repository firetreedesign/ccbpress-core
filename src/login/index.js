/**
 * BLOCK: login
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import "./style.scss";
import "./editor.scss";

import blockIcons from "../icons.js";

const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType("ccbpress/login", {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __("CCB Login"), // Block title.
  icon: blockIcons.ccbpress, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: "widgets", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [__("church community builder"), __("ccb"), __("ccbpress")],
  supports: {
    html: false
  },

  edit: props => {
    return (
      <div className={props.className} title="title">
        <form class="ccbpress-core-login" method="post" target="_blank">
          <fieldset disabled="true">
            <label>{__("Username:")}</label>
            <input type="text" value="" />
            <label>{__("Password:")}</label>
            <input type="password" value="" />
            <input type="submit" value={__("Login")} />
          </fieldset>
        </form>
        <p>
          <a href="#" onClick="return false;">
            {__("Forgot username or password?")}
          </a>
        </p>
      </div>
    );
  },

  /**
   * The save function defines the way in which the different attributes should be combined
   * into the final markup, which is then serialized by Gutenberg into post_content.
   *
   * The "save" property must be specified and must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  save() {
    // Rendering in PHP
    return null;
  }
});
