import React from 'react';
import Loadable from 'react-loadable';
import { connect } from 'react-redux';
import Waypoint from 'react-waypoint';

import { IComponentEvent } from '@root/components/Component._types';
import { ILanguageEntity } from '@root/connectors/backend/BookApiConnector._types';
import { snakeCasePropsToCamelCase } from '@root/utilities/func/snake-case';
import SharedReference from '@root/utilities/SharedReference';
import { SearchActions } from '../actions';
import { IRootReducer } from '../reducers';
import { IProps, IState } from './Glossary._types';

import Spinner from '@root/components/Spinner';
import Language from '../components/Language';

import './Glossary.scss';

export class Glossary extends React.PureComponent<IProps, IState> {
    public state: IState = {
        notifyLoaded: true,
    };

    private _actions = new SharedReference(SearchActions);

    public componentWillMount() {
        this._initializePreloadedGlossary();
        this._removeGlossaryForBots();
    }

    public render() {
        const { isEmpty, loading, word } = this.props;

        if (loading) {
            return <Spinner />;
        }

        if (!word || word.length < 1) {
            return null;
        }

        if (isEmpty) {
            return this._renderEmptyDictionary();
        }

        return this._renderDictionary();
    }

    private _renderEmptyDictionary() {
        return <div>
            <h3>Alas! What you are looking for does not exist!</h3>
            <p>The word <em>{this.props.word}</em> does not exist in the dictionary.</p>
        </div>;
    }

    private _renderDictionary() {
        return <React.Fragment>
            {this.state.notifyLoaded && <FixedBouncingArrow />}
            <Waypoint onEnter={this._onWaypointEnter} onLeave={this._onWaypointLeave}>
                <article>
                    {this._renderCommonLanguages()}
                    {this._renderUnusualLanguages()}
                </article>
            </Waypoint>
        </React.Fragment>;
    }

    private _renderCommonLanguages() {
        if (this.props.languages.length === 0) {
            return null;
        }

        return this._renderLanguages(this.props.languages);
    }

    private _renderUnusualLanguages() {
        if (this.props.unusualLanguages.length === 0) {
            return null;
        }

        const abstract = <p>
            <strong>Beware, older languages below!</strong> {' '}
            The languages below were invented during Tolkien's earlier period and should be used with caution. {' '}
            Remember to never, ever mix words from different languages!
        </p>;
        return this._renderLanguages(this.props.unusualLanguages, abstract, ['ed-glossary--unusual']);
    }

    private _renderLanguages(languages: ILanguageEntity[], abstract: React.ReactNode = null, //
        classNames: string[] = []) {
        classNames = [ 'ed-glossary', ...classNames ];
        if (this.props.single) {
            classNames.push('ed-glossary--single');
        }

        return <section className={classNames.join(' ')}>
            {abstract}
            {languages.map(
                (language) => <Language key={language.id} language={language}
                    glosses={this.props.glosses[language.id]} onReferenceLinkClick={this._onReferenceClick} />,
            )}
        </section>;
    }

    /**
     * Initializes the component with the glossary supplied to it from the server. This is
     * a common scenario when the client reloads the browser (and consequently loses JS in-memory state).
     * In this scenario, the server will provide the glossary for the page that was just loaded, and the
     * JavaScript client is responsible to pick it up and use it to restore its previous in-memory state.
     */
    private _initializePreloadedGlossary() {
        const preloadedGlossary = this._getPreloadedGlossary();
        if (preloadedGlossary === null) {
            return;
        }

        this.props.dispatch(
            this._actions.value.setGlossary(preloadedGlossary),
        );
    }

    /**
     * Gets the text content of an arbitrary element with the id `ed-preloaded-book` and deserializes
     * it using the JSON serializer. The element is expected to contain a full glossary API request
     * response.
     */
    private _getPreloadedGlossary() {
        const stateContainer = document.getElementById('ed-preloaded-book');
        if (!stateContainer) {
            return null;
        }

        try {
            const glossary = JSON.parse(stateContainer.textContent);

            // The glossary is preloaded by the server, so its properties are `snake_case`.
            // Consequently, we must convert them to `camelCase` which is recognised by the view.
            return snakeCasePropsToCamelCase<any>(glossary);
        } catch (e) {
            // We do not really care about these errors -- just silence the exception when
            // the format is unrecognised.
            console.warn(e);
            return null;
        }
    }

    /**
     * Removes an element with the id `ed-book-for-bots` which is a partial server-side rendering
     * of the glossary intended for search indexing purposes.
     */
    private _removeGlossaryForBots() {
        // has the server added a glossary intended for bots (such as Google)?
        // remove them, if such is the case:
        const glossaryForBots = document.getElementById('ed-book-for-bots');
        if (glossaryForBots) {
            glossaryForBots.parentElement.removeChild(glossaryForBots);
        }
    }

    /**
     * Default event handler for reference link clicks.
     */
    private _onReferenceClick = async (ev: IComponentEvent<{
        languageShortName: string;
        normalizedWord: string;
        word: string;
    }>) => {
        this.props.dispatch(
            this._actions.value.loadReference(ev.value.word, ev.value.normalizedWord, ev.value.languageShortName),
        );
    }

    /**
     * `Waypoint` default event handler for the `enter` event. It is used to track the notifier arrow,
     * and hiding it when the user is viewing the glossary.
     */
    private _onWaypointEnter = () => {
        const notifyLoaded = false;

        if (this.state.notifyLoaded !== notifyLoaded) {
            this.setState({
                notifyLoaded,
            });
        }
    }

    /**
     * `Waypoint` default event handler for the `leave` event. It is used to track the notifier arrow,
     * and showing it when the component is below the viewport.
     */
    private _onWaypointLeave = (ev: Waypoint.CallbackArgs) => {
        // `currentPosition` is the position of the element relative to the viewport.
        const notifyLoaded = (ev.currentPosition === Waypoint.below);

        if (this.state.notifyLoaded !== notifyLoaded) {
            this.setState({
                notifyLoaded,
            });
        }
    }
}

const FixedBouncingArrow = Loadable({
    loader: () => import('@root/components/BouncingArrow'),
    loading: () => <React.Fragment />,
    render: (loaded, props) => {
        const Component = loaded.default;

        return <div className="ed-glossary-loaded-notifier">
            <Component {...props} />
        </div>;
    },
});

const mapStateToProps = (state: IRootReducer) => ({
    ...state.glossary,

    glosses: state.glosses,
    isEmpty: state.languages.isEmpty,
    languages: state.languages.common,
    unusualLanguages: state.languages.unusual,
});

export default connect(mapStateToProps)(Glossary);