/**
 * BLOCK: Group Info
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import "./style.scss";
import "./editor.scss";

import blockIcons from "../icons.js";
import CCBPressGroupSelect from "../components/ccbpress-group-select.js";
import { getGroup, isFormActive } from "../utils/data.js";

const { Component, Fragment } = wp.element;
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { InspectorControls, PanelColorSettings } = wp.blockEditor;
const {
  PanelBody,
  ToggleControl,
  Spinner,
  Placeholder,
  Disabled,
} = wp.components;

class CCBPressGroupInfoBlock extends Component {
  constructor() {
    super(...arguments);
    this.state = {
      group: null,
      prevGroupId: null,
      isLoading: true,
      externalData: null,
    };
  }

  static getDerivedStateFromProps(nextProps, prevState) {
    // Store prevId in state so we can compare when props change.
    // Clear out previously-loaded data (so we don't render stale stuff).
    if (nextProps.attributes.groupId !== prevState.prevGroupId) {
      return {
        group: null,
        prevGroupId: nextProps.attributes.groupId,
        isLoading: true,
      };
    }

    // No state update necessary
    return null;
  }

  componentDidMount() {
    this._getGroupInfo();
  }

  componentDidUpdate(prevProps, prevState) {
    const { groupId } = this.props.attributes;
    if (this.state.group === null && groupId !== null) {
      this._getGroupInfo();
    }
  }

  _getGroupInfo() {
    const { groupId } = this.props.attributes;

    if (groupId === null) {
      this.setState({ isLoading: false });
      return;
    }

    getGroup(groupId)
      .then((response) => response.json())
      .then((data) => {
        // console.log(data.data);
        this.setState({ group: data, isLoading: false });
      });
  }

  _renderPhoneNumbers(phones) {
    let phones_array = [];

    if (this._isEmptyObject(phones)) {
      return phones_array;
    }

    for (const prop in phones) {
      if (typeof phones[prop] === "string" || phones[prop] instanceof String) {
        phones_array.push(
          <div class="ccbpress-group-info-leader-phone">{phones[prop]}</div>
        );
      }
    }

    return phones_array;
  }

  _isEmptyObject(obj) {
    if (Object.keys(obj).length === 0 && obj.constructor === Object) {
      return true;
    }

    return false;
  }

  _renderRegistrationForms(forms) {
    let forms_array = [];

    if (this._isEmptyObject(forms)) {
      return forms_array;
    }

    for (var key in forms) {
      if (!forms.hasOwnProperty(key)) {
        continue;
      }

      if (!isFormActive(forms[key]["@attributes"]["id"])) {
        continue;
      }

      forms_array.push(
        <div class="ccbpress-group-info-registration-form">
          <a href={forms[key]["url"].toString()}>
            {forms[key]["name"].toString()}
          </a>
        </div>
      );
    }

    return forms_array;
  }

  _setBoxBackgroundColor = (color) => {
    this.props.setAttributes({ boxBackgroundColor: color });
  };

  _setBoxBorderColor = (color) => {
    this.props.setAttributes({ boxBorderColor: color });
  };

  render() {
    const { attributes, setAttributes, className } = this.props;
    const {
      groupId,
      showGroupImage,
      showGroupName,
      showGroupDesc,
      showMainLeader,
      showMainLeaderEmail,
      showMainLeaderPhone,
      showRegistrationForms,
      boxBackgroundColor,
      boxBorderColor,
    } = attributes;

    const inspectorControls = (
      <InspectorControls key="inspector">
        <PanelBody title={__("Group Settings")}>
          <CCBPressGroupSelect
            value={groupId}
            onChange={(value) => setAttributes({ groupId: value })}
          />
        </PanelBody>
        <PanelBody title={__("Display Settings")}>
          <ToggleControl
            label={__("Image")}
            checked={showGroupImage}
            onChange={() => setAttributes({ showGroupImage: !showGroupImage })}
          />
          <ToggleControl
            label={__("Group Name")}
            checked={showGroupName}
            onChange={(checked) => setAttributes({ showGroupName: checked })}
          />
          <ToggleControl
            label={__("Description")}
            checked={showGroupDesc}
            onChange={(checked) => setAttributes({ showGroupDesc: checked })}
          />
          <ToggleControl
            label={__("Main Leader")}
            checked={showMainLeader}
            onChange={(checked) => setAttributes({ showMainLeader: checked })}
          />
          {showMainLeader && (
            <ToggleControl
              label={__("Email Address")}
              checked={showMainLeaderEmail}
              onChange={(checked) =>
                setAttributes({ showMainLeaderEmail: checked })
              }
            />
          )}
          {showMainLeader && (
            <ToggleControl
              label={__("Phone Numbers")}
              checked={showMainLeaderPhone}
              onChange={(checked) =>
                setAttributes({ showMainLeaderPhone: checked })
              }
            />
          )}
          <ToggleControl
            label={__("Registration Forms")}
            checked={showRegistrationForms}
            onChange={(checked) =>
              setAttributes({ showRegistrationForms: checked })
            }
          />
        </PanelBody>
        <PanelColorSettings
          title={__("Meta Box Colors")}
          initialOpen={false}
          colorSettings={[
            {
              value: boxBackgroundColor,
              onChange: this._setBoxBackgroundColor,
              label: __("Background Color"),
            },
            {
              value: boxBorderColor,
              onChange: this._setBoxBorderColor,
              label: __("Border Color"),
            },
          ]}
        />
      </InspectorControls>
    );

    if (this.state.isLoading) {
      return (
        <Fragment>
          {inspectorControls}
          <Placeholder
            icon={blockIcons.ccbpress}
            label={__("Group Information")}
          >
            <Spinner />
          </Placeholder>
        </Fragment>
      );
    }

    return (
      <Fragment>
        {inspectorControls}
        <div className={className}>
          {(groupId === null || groupId === "") && (
            <Placeholder
              icon={blockIcons.ccbpress}
              label={__("Group Information")}
            >
              <CCBPressGroupSelect
                value={groupId}
                onChange={(value) => setAttributes({ groupId: value })}
              />
            </Placeholder>
          )}
          <Disabled>
            {groupId !== null && groupId !== "" && (
              <div>
                {showGroupImage && this.state.group.image !== "" && (
                  <div>
                    <img src={this.state.group.image} />
                  </div>
                )}
                {showGroupName &&
                  !this._isEmptyObject(this.state.group.data.name) && (
                    <div className="ccbpress-group-info-name">
                      {this.state.group.data.name}
                    </div>
                  )}
                {showGroupDesc &&
                  !this._isEmptyObject(this.state.group.data.description) && (
                    <div className="ccbpress-group-info-desc">
                      {this.state.group.data.description}
                    </div>
                  )}
                <div
                  className="ccbpress-group-info-details"
                  style={{
                    backgroundColor: boxBackgroundColor
                      ? boxBackgroundColor
                      : "",
                    borderColor: boxBorderColor ? boxBorderColor : "",
                  }}
                >
                  {showMainLeader && (
                    <div>
                      <div className="ccbpress-group-info-leader-title">
                        {__("Group Leader")}
                      </div>
                      <div className="ccbpress-group-info-leader-container">
                        {this.state.group.data.main_leader.image &&
                          this.state.group.data.main_leader.image.length >
                            3 && (
                            <img
                              className="ccbpress-group-info-leader-image"
                              src={this.state.group.data.main_leader.image}
                            />
                          )}
                        <div className="ccbpress-group-info-leader-name">
                          {showMainLeaderEmail && (
                            <a
                              href={`mailto:${this.state.group.data.main_leader.email.toString()}`}
                            >
                              {this.state.group.data.main_leader.full_name.toString()}
                            </a>
                          )}
                          {!showMainLeaderEmail && (
                            <Fragment>
                              {this.state.group.data.main_leader.full_name.toString()}
                            </Fragment>
                          )}
                        </div>
                      </div>
                      {showMainLeaderPhone &&
                        this._renderPhoneNumbers(
                          this.state.group.data.main_leader.phones
                        )}
                    </div>
                  )}
                  {showRegistrationForms &&
                    !this._isEmptyObject(
                      this.state.group.data.registration_forms
                    ) && (
                      <div>
                        <div className="ccbpress-group-info-registration-forms-title">
                          {__("Registration Forms")}
                        </div>
                        {this._renderRegistrationForms(
                          this.state.group.data.registration_forms
                        )}
                      </div>
                    )}
                </div>
              </div>
            )}
          </Disabled>
        </div>
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
export default registerBlockType("ccbpress/group-info", {
  // Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
  title: __("Group Information"), // Block title.
  description: __("Display group information from Church Community Builder."),
  icon: blockIcons.ccbpress, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.
  category: "ccbpress", // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
  keywords: [__("church community builder"), __("ccb"), __("ccbpress")],
  supports: {
    html: false,
  },
  attributes: {
    groupId: {
      type: "select",
      default: null,
    },
    showGroupImage: {
      type: "boolean",
      default: true,
    },
    showGroupName: {
      type: "boolean",
      default: true,
    },
    showGroupDesc: {
      type: "boolean",
      default: true,
    },
    showMainLeader: {
      type: "boolean",
      default: true,
    },
    showMainLeaderEmail: {
      type: "boolean",
      default: true,
    },
    showMainLeaderPhone: {
      type: "boolean",
      default: true,
    },
    showRegistrationForms: {
      type: "boolean",
      default: true,
    },
    boxBackgroundColor: {
      type: "string",
    },
    boxBorderColor: {
      type: "string",
    },
  },

  edit: CCBPressGroupInfoBlock,

  save() {
    return;
  },
});
