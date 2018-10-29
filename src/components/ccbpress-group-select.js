import { getGroups } from "../utils/data.js";

const { __ } = wp.i18n;
const { Component } = wp.element;
const { SelectControl } = wp.components;

export default class CCBPressGroupSelect extends Component {
	constructor(props) {
		super(...arguments);
		this.state = {
			options: [{ id: "", name: __("Loading groups...") }]
		};

		getGroups()
			.then(response => response.json())
			.then(options => {
				options.unshift({ id: "", name: "" });
				// console.log(options);
				return this.setState({ options });
			});
	}

	render() {
		return (
			<SelectControl
				type="number"
				label={__("Please select a group:")}
				value={this.props.value}
				onChange={this.props.onChange}
				options={this.state.options.map(option => {
					return { value: option.id, label: option.name };
				})}
			/>
		);
	}
}
