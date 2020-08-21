/**
 * BLOCK: online-giving
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
  PanelColorSettings,
  ContrastChecker,
} = wp.blockEditor;
const { PanelBody, TextControl, Disabled } = wp.components;

class CCBPressOnlineGivingBlock extends Component {
  constructor() {
    super(...arguments);
  }

  _setBackgroundColor = (color) => {
    this.props.setAttributes({ backgroundColor: color });
  };

  _setTextColor = (color) => {
    this.props.setAttributes({ textColor: color });
  };

  render() {
    const { attributes, setAttributes, className } = this.props;
    const { buttonText, backgroundColor, textColor } = attributes;

    const inspectorControls = (
      <InspectorControls key="inspector">
        <PanelBody title={__("Button Settings")}>
          <TextControl
            label={__("Button Text")}
            value={buttonText}
            onChange={(buttonText) => setAttributes({ buttonText })}
          />
        </PanelBody>
        <PanelColorSettings
          title={__("Button Colors")}
          initialOpen={false}
          colorSettings={[
            {
              value: backgroundColor,
              onChange: this._setBackgroundColor,
              label: __("Background Color"),
            },
            {
              value: textColor,
              onChange: this._setTextColor,
              label: __("Text Color"),
            },
          ]}
        >
          <ContrastChecker
            {...{
              textColor: textColor,
              backgroundColor: backgroundColor,
            }}
          />
        </PanelColorSettings>
      </InspectorControls>
    );

    return (
      <Fragment>
        {inspectorControls}
        <Disabled>
          <div className={className}>
            <form className="ccbpress-core-online-giving" target="_blank">
              <input
                type="submit"
                value={buttonText}
                style={{
                  backgroundColor: backgroundColor ? backgroundColor : "",
                  color: textColor ? textColor : "",
                }}
              />
            </form>
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
registerBlockType("ccbpress/online-giving", {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __("Online Giving"), // Block title.
  icon: blockIcons.ccbpress, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: "ccbpress", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [__("church community builder"), __("ccb"), __("ccbpress")],
  supports: {
    html: false,
  },
  attributes: {
    buttonText: {
      type: "string",
      default: __("Give Now"),
    },
    backgroundColor: {
      type: "string",
    },
    textColor: {
      type: "string",
    },
  },

  /**
   * The edit function describes the structure of your block in the context of the editor.
   * This represents what the editor will render when the block is used.
   *
   * The "edit" property must be a valid function.
   *
   * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
   */
  edit: CCBPressOnlineGivingBlock,

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
  },
});
