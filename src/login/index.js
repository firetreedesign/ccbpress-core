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

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n; // Import __() from wp.i18n
const { registerBlockType } = wp.blocks; // Import registerBlockType() from wp.blocks
const {
  InspectorControls,
  ContrastChecker,
  PanelColorSettings,
} = wp.blockEditor;
const { PanelBody, ToggleControl, TextControl, Disabled } = wp.components;

class CCBPressLoginBlock extends Component {
  constructor() {
    super(...arguments);
  }

  _setButtonBackgroundColor = (color) => {
    this.props.setAttributes({ buttonBackgroundColor: color });
  };

  _setButtonTextColor = (color) => {
    this.props.setAttributes({ buttonTextColor: color });
  };

  render() {
    const { attributes, setAttributes, className } = this.props;
    const {
      showForgotPassword,
      buttonBackgroundColor,
      buttonTextColor,
      buttonText,
    } = attributes;

    const inspectorControls = (
      <InspectorControls key="inspector">
        <PanelBody title={__("Form Settings")}>
          <ToggleControl
            label={__("Show Forgot Password")}
            checked={showForgotPassword}
            onChange={() =>
              setAttributes({ showForgotPassword: !showForgotPassword })
            }
          />
          <TextControl
            label={__("Submit Button Text")}
            value={buttonText}
            onChange={(buttonText) => setAttributes({ buttonText })}
          />
        </PanelBody>
        <PanelColorSettings
          title={__("Submit Button Colors")}
          initialOpen={false}
          colorSettings={[
            {
              value: buttonBackgroundColor,
              onChange: this._setButtonBackgroundColor,
              label: __("Background Color"),
            },
            {
              value: buttonTextColor,
              onChange: this._setButtonTextColor,
              label: __("Text Color"),
            },
          ]}
        >
          <ContrastChecker
            {...{
              textColor: buttonTextColor,
              backgroundColor: buttonBackgroundColor,
            }}
          />
        </PanelColorSettings>
      </InspectorControls>
    );

    return (
      <Fragment>
        {inspectorControls}
        <Disabled>
          <div className={className} title="title">
            <form class="ccbpress-core-login" method="post" target="_blank">
              <label>{__("Username:")}</label>
              <input type="text" value="" />
              <label>{__("Password:")}</label>
              <input type="password" value="" />
              <input
                type="submit"
                value={buttonText}
                style={{
                  backgroundColor: buttonBackgroundColor
                    ? buttonBackgroundColor
                    : "",
                  color: buttonTextColor ? buttonTextColor : "",
                }}
              />
            </form>
            {showForgotPassword && (
              <p>
                <a href="#">{__("Forgot your password?")}</a>
              </p>
            )}
          </div>
        </Disabled>
      </Fragment>
    );
  }
}

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
  category: "ccbpress", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [__("church community builder"), __("ccb"), __("ccbpress")],
  supports: {
    html: false,
  },
  attributes: {
    showForgotPassword: {
      type: "boolean",
      default: true,
    },
    buttonBackgroundColor: {
      type: "string",
    },
    buttonTextColor: {
      type: "string",
    },
    buttonText: {
      type: "string",
      default: __("Login"),
    },
  },

  edit: CCBPressLoginBlock,

  save() {
    // Rendering in PHP
    return null;
  },
});
