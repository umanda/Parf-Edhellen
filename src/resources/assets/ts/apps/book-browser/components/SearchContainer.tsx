import React from 'react';
import {
    connect,
    DispatchProp,
} from 'react-redux';

import { IChangeEvent } from '../../../components/FormComponent';
import LanguageSelect from '../../../components/LanguageSelect';
import debounce from '../../../utilities/func/debounce';
import { ISearchActionState } from '../_types';
import { SearchActions } from '../actions';

import SearchQueryInput from './SearchQueryInput';

export class SearchContainer extends React.PureComponent<DispatchProp, ISearchActionState> {
    public state: ISearchActionState = {
        includeOld: false,
        languageId: 0,
        query: '',
        reversed: false,
    };

    private _actions = new SearchActions();
    private _beginSearch = debounce(500, this._search);

    public render() {
        return <form onSubmit={this._onSubmit}>
            <div className="row">
                <div className="col-md-12">
                    <SearchQueryInput
                        autoFocus={true}
                        name="query"
                        onChange={this._onQueryChange}
                        tabIndex={1}
                        value={this.state.query}
                    />
                </div>
            </div>
            <div className="row">
                <div className="search-language-select">
                    <label className="inline input-sm">
                        <input checked={this.state.reversed}
                            name="reversed"
                            onChange={this._onReverseChange}
                            type="checkbox"
                        /> Reversed
                    </label>
                    <label className="inline input-sm">
                        <input checked={this.state.includeOld}
                            name="excludeOld"
                            onChange={this._onIncludeOldChange}
                            type="checkbox"
                        /> Old sources
                    </label>
                    <LanguageSelect
                        name="languageId"
                        onChange={this._onLanguageChange}
                        value={this.state.languageId}
                    />
                </div>
            </div>
        </form>;
    }

    private _onQueryChange = (ev: IChangeEvent<string>) => {
        this.setState({
            query: ev.value,
        });

        this._beginSearch();
    }

    private _onReverseChange = (ev: React.ChangeEvent<HTMLInputElement>) => {
        this.setState({
            reversed: ev.target.checked,
        });

        this._beginSearch();
    }

    private _onIncludeOldChange = (ev: React.ChangeEvent<HTMLInputElement>) => {
        this.setState({
            includeOld: ev.target.checked,
        });

        this._beginSearch();
    }

    private _onLanguageChange = (ev: IChangeEvent<number>) => {
        this.setState({
            languageId: ev.value,
        });

        this._beginSearch();
    }

    private _onSubmit = (ev: React.FormEvent<HTMLFormElement>) => {
        ev.preventDefault();
    }

    private _search() {
        console.log(this.state);
    }
}

const mapStateToProps = (state: any) => {
    return {};
};

export default connect(mapStateToProps)(SearchContainer);
