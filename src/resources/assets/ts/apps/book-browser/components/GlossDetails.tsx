import React from 'react';

import HtmlInject from '../../../components/HtmlInject';
import { IProps } from './GlossDetails._types';

const GlossDetails = (props: IProps) => {
    const {
        gloss,
        onReferenceLinkClick,
        showDetails,
    } = props;

    return <React.Fragment>
        <HtmlInject html={gloss.comments} onReferenceLinkClick={onReferenceLinkClick} />

        {showDetails && gloss.glossDetails.map(
            (d) => <section className="details" key={d.order}>
                <header>
                    <h4>{d.category}</h4>
                </header>
                <div>
                    <HtmlInject html={d.text} onReferenceLinkClick={onReferenceLinkClick} />
                </div>
            </section>,
        )}
    </React.Fragment>;
};

GlossDetails.defaultProps = {
    showDetails: true,
};

export default GlossDetails;
