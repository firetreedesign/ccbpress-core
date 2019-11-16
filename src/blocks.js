/**
 * Import CCBPress blocks
 */
import './login';
import './online-giving';
import './group-info';
import blockIcons from './icons.js';

(function() {
	wp.blocks.updateCategory('ccbpress', { icon: blockIcons.ccbpress });
})();
